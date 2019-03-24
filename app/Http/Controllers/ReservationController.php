<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Alert;
use Carbon\Carbon;
use Mail;
use App\Models\AvailableTime;
use App\Models\Reservation;
use App\Models\ReservationUser;
use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Models\TrainingAttendanceUser;
use App\Models\User;
use App\Models\Identity;
use Validator;
use PDF;

class ReservationController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        
        $request->session()->forget('reservation');

        $data['reservations'] = Reservation::filter($request)->orderBy('reservation_date', 'asc')->paginate(20);
        return view('reservations.index', $data);
    }

    public function create(Request $request)
    {
        if (!$request->session()->has('reservation')) {
            $request->session()->put('reservation.step', 1);
        }

        $view = '';
        $data = [];

        $step = $request->has('step') ? $request->get('step') : ($request->session()->has('reservation.step') ? $request->session()->get('reservation.step') : 1);

        if ($step > $request->session()->get('reservation.step')) {
            $step = $request->session()->get('reservation.step');

            return redirect()->route("reservations.booking-wizard", "step={$step}");
        }

        switch($step) {
            case 1:
            default:
                $view = 'reservations.form-wizard.step-1';

                $date = Carbon::now();
                $user = User::find(Auth::user()->id);

                $trainings = Training::where([
                    ['site_id', '=', $user->identity->site_id],
                    ['training_status', '=', true]
                ])->whereDate('end_date', '>', $date->format('Y-m-d'))->get();

                $data = [
                    'user' => $user,
                    'trainings' => $trainings
                ];
            break;
            case 2:
                $view = 'reservations.form-wizard.step-2';
            break;
            case 3:
                $view = 'reservations.form-wizard.step-3';

                $user = User::find(Auth::user()->id);
                $userlists = Identity::where([
                    ['site_id', '=', $user->identity->site_id],
                    ['id', '<>', $user->identity->id]
                ])->get();

                $training = Training::find($request->session()->get('reservation.training_id'));
                $training->load([
                    'choosen_times' => function ($query) {
                        $query->orderBy('start_time', 'asc');
                    }
                ]);

                $data = [
                    'user' => $user,
                    'userlists' => $userlists,
                    'training' => $training
                ];
            break;
        }

        return view($view, $data);
    }

    public function formWizardTraining(Request $request)
    {
        if (!$request->isMethod('post')) {
            Alert::error('Method not allowed', 'Error 405');

            return redirect()->back();
        }

        $request->session()->put('reservation.training_id', $request->input('training_id'));

        $request->session()->put('reservation.step', 2);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'go to Next Step'
        ]);
    }

    public function formWizardGroup(Request $request)
    {
        if (!$request->isMethod('post')) {
            Alert::error('Method not allowed', 'Error 405');

            return redirect()->back();
        }

        if (!$request->session()->has('reservation')) {
            $request->session()->push('reservation.type', $request->input('reservation_type'));
        }
        else {
            $request->session()->put('reservation.type', $request->input('reservation_type'));
        }
        $request->session()->put('reservation.step', 3);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'go to Next Step'
        ]);        
    }

    public function formWizardConfirm(Request $request)
    {
        if (!$request->isMethod('post')) {
            Alert::error('Method not allowed', 'Error 405');

            return redirect()->back()->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::find(Auth::user()->id);

            $reservation = new Reservation();
            $reservation->training_id = $request->session()->get('reservation.training_id');
            $reservation->user_identity_id = $user->identity->id;
            $reservation->reservation_type = $request->session()->get('reservation.type');
            $reservation->reservation_date = $request->input('reservation_date');
            $reservation->reservation_time_id = $request->input('reservation_time');
            $reservation->manager_email = $request->input('manager_email');
            $reservation->user_log = Auth::user()->username;

            $reservation->save();

            $reservation_user = new ReservationUser();
            $reservation_user->user_identity_id = $user->identity->id;
            $reservation->reservation_users()->save($reservation_user);

            $attendance = TrainingAttendance::firstOrNew(
                [
                    'training_id' => $reservation->training_id,
                    'training_attendance_date' => $reservation->reservation_date,
                    'training_attendance_time_id' => $reservation->reservation_time_id,
                ]
            );

            $attendance->save();

            $participant = new TrainingAttendanceUser();
            $participant->reservation_id = $reservation->id;
            $participant->identity_id = $reservation_user->user_identity_id;
            $participant->user_log = Auth::user()->username;

            $attendance->participants()->save($participant);

            if ($request->session()->get('reservation.type') == 'group') {
                foreach ($request->input('reservation_user') as $key => $value) {
                    $reservation_user = new ReservationUser();
                    $reservation_user->user_identity_id = $value;
                    $reservation_user->reservation_user_as = 'member';

                    $reservation->reservation_users()->save($reservation_user);

                    $participants = new TrainingAttendanceUser();
                    $participants->reservation_id = $reservation->id;
                    $participants->identity_id = $value;
                    $participant->user_log = Auth::user()->username;

                    $attendance->participants()->save($participants);
                }
            }

            DB::commit();

            Alert::success('Booking Completed', 'Success');

            foreach ($reservation->reservation_users as $user) {
                $identity = Identity::find($user->user_identity_id);

                $data = [
                    'reservation' => $reservation,
                    'identity' => $identity,
                ];

                if (!empty($identity->email)) {
                    Mail::send('mails.reservation-apply', $data, function ($message) use($request, $identity) {
                        $message->to($identity->email);
                        $message->subject('Booking Training Confirmation');
                    });
                }
        
            }

            $data = [
                'reservation' => $reservation,
                'identity' => $reservation->identity,
                'time' => AvailableTime::find($reservation->reservation_time_id)
            ];

            Mail::send('mails.reservation-apply-manager', $data, function ($message) use($request) {
                $message->to($request->input('manager_email'));
                $message->subject('Booking Training Confirmation');
            });

            $request->session()->flash('reservation_id', $reservation->id);

            return view('reservations.form-wizard.confirm', $data);

        }
        catch(QueryException $e) {
            DB::rollback();

        }

    }

    public function print(Request $request, $id)
    {
        $reservation = Reservation::with('training')->find($id);

        PDF::setPrintHeader(false);
        PDF::setPrintFooter(false);

        PDF::SetTitle('Booking Confirmation');
        PDF::AddPage('L', 'A5');
        
        PDF::SetFont('HelveticaB');
        PDF::SetFontSize(12);

        //PDF::Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        PDF::Cell(190, 10, 'Booking Confirmation', '', '');
        PDF::Ln();
        PDF::Line(200, 20, 10, 20, ['width' => '0.1', 'color' => array(128, 128, 128)]);
        PDF::Ln(10);

        PDF::SetFont('Helvetica');
        // PDF::SetFontSize(10);
        PDF::Cell(190, 10, "Dear {$reservation->identity->full_name}", '', '');
        PDF::Ln();
        PDF::Cell(190, 10, "Anda sudah terdaftar untuk pelatihan {$reservation->training->training_name} yang akan dilaksanakan pada :", '', '');
        PDF::Ln(15);

        PDF::SetX(30);
        PDF::Cell(50, 10, "Tanggal", '', '');
        PDF::Cell(50, 10, "{$reservation->reservation_date}", '', '');
        PDF::Ln();
        
        PDF::SetX(30);
        PDF::Cell(50, 10, "Waktu", '', '');
        PDF::Cell(50, 10, "{$reservation->time->title}", '', '');
        PDF::Ln();

        PDF::SetX(30);
        PDF::Cell(50, 10, "Tempat", '', '');
        PDF::Cell(50, 10, "{$reservation->training->class->class_name} {$reservation->training->site->site_name}", '', '');
        PDF::Ln(15);

        PDF::Cell(190, 10, "Kami tunggu kedatangan Bapak / Ibu. Terima kasih.", '', '');
        PDF::Ln(15);

        PDF::Cell(180, 10, "Directorat IT", '', '', 'R');

        PDF::Output('booking-confirmation.pdf');
    }

    public function validateReservation(Request $request)
    {
        if (!$request->isMethod('get')) {
            Alert::error('Method not allowed', 'Error 405')->autoclose(false);

            return response()->json([
                'code' => 405,
                'status' => 'error',
                'message' => 'Method not allowed'
            ]);
        }

        $request->session()->put('reservation.validate', false);

        $query = Training::with([
            'reservations' => function ($reservations) use ($request) {
                $reservations->whereDate('reservation_date', $request->input('reservation_date'));
            }
        ])->find($request->input('training_id'));

        $start_date = new Carbon($query->start_date);
        $end_date = new Carbon($query->end_date);
        $reservation_date = new Carbon($request->input('reservation_date'));

        /**
         * Tanggal reservasi < tanggal awal training
         */
        if ($start_date->greaterThanOrEqualTo($reservation_date)) {
            return response()->json([
                'code' => 407,
                'status' => 'error',
                'message' => 'Tanggal reservasi harus setelah tanggal Training dibuka'
            ]);
        }

        /**
         * tanggal reservasi melebihi batas akhir training
         */
        if ($end_date->lessThan($reservation_date)) {
            return response()->json([
                'code' => 407,
                'status' => 'error',
                'message' => 'Tanggal Reservasi melebihi batas akhir training'
            ]);
        }

        if ($query->max_quote - $query->reservations->sum('user_count') <= 0) {
            return response()->json([
                'code' => 407,
                'status' => 'warning',
                'message' => 'Quota Habis!'
            ]);
        }

        $request->session()->put('reservation.validate', true);

        $data = [
            'max_quote' => $query->max_quote,
            'booked' => $query->reservations->sum('user_count'),
            'available_quote' => $query->max_quote - $query->reservations->sum('user_count'),
            'reservation_date' => $reservation_date->format('Y-m-d'),
        ];

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Quota tersedia',
            'data' => $data,
            'session' => $request->session()->get('reservation')
        ]);
    }

    public function show($id)
    {
        $data['reservation'] = Reservation::find($id);

        return view('reservations.show', $data);
    }

    public function reschedule(Request $request, $id)
    {
        $data['reservation'] = Reservation::find($id);

        if (!$data['reservation']->canModify()) {
            Alert::error('Batas Akhir Reschedule 5 hari sebelum hari-H');
            return redirect()->route('reservations');
        }

        $data['training'] = Training::find($data['reservation']->training_id);
        $data['training']->load([
            'choosen_times' => function ($query) {
                $query->orderBy('start_time', 'asc');
            }
        ]);
        
        return view('reservations.reschedule', $data);
    }

    public function rescheduleSubmit(Request $request, $id)
    {
        if (!$request->isMethod('put')) {
            Alert::error('Method not allowed', 'Error 405')->autoclose(false);

            return redirect()->back()->withInput();
        }

        $reservation = Reservation::find($id);

        $validated = Validator::make(
            $request->input(),
            [
                'reservation_date' => [
                    'required',
                    'date',
                    'before:' . $reservation->training->end_date,
                    'after:' . $reservation->training->start_date,
                ],
                'reservation_time' => 'required',
                'manager_email' => 'required|email'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()->withError($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $old_reservation = $reservation->getOriginal();

            $reservation->reservation_date = $request->input('reservation_date');
            $reservation->reservation_time_id = $request->input('reservation_time');
            $reservation->manager_email = $request->input('manager_email');

            $reservation->save();

            $attendance = TrainingAttendance::firstOrNew([
                'training_id' => $reservation->training_id,
                'training_attendance_date' => $reservation->reservation_date,
                'training_attendance_time_id' => $reservation->reservation_time_id,
            ]);

            $attendance->save();

            foreach ($reservation->reservation_users as $user) {
                $identity = Identity::find($user->user_identity_id);

                $participants = TrainingAttendanceUser::firstOrNew(
                    [
                        'reservation_id' => $reservation->id,
                        'identity_id' => $user->user_identity_id
                    ]
                );

                $attendance->participants()->save($participants);

                $data = [
                    'reservation' => $reservation,
                    'identity' => $identity,
                    'old_reservation' => $old_reservation
                ];

                if (!empty($identity->email)) {
                    Mail::send('mails.reservation-reschedule', $data, function ($message) use($request, $identity) {
                        $message->to($identity->email);
                        $message->subject('Training Reschedule Notification');
                    });
                }

            }

            $empty_attendance = TrainingAttendance::doesntHave('participants')->delete();

            DB::commit();

            $data = [
                'reservation' => $reservation,
                'identity' => $reservation->identity,
                'old_reservation' => $old_reservation
            ];

            Mail::send('mails.reservation-reschedule-manager', $data, function ($message) use($request) {
                $message->to($request->input('manager_email'));
                $message->subject('Training Reschedule Notification');
            });

            Alert::success('Training Rescheduled', 'Success')->autoclose(3000);

            return redirect()->route('reservations');

        }
        catch(QueryException $e) {
            DB::rollback();

            return $e->getCode() . ' ' . $e->getMessage();

            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);

            return redirect()->back()->withInput();
        }

    }

    public function generateAttendance(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $reservation = Reservation::find($id);
            $attendance = TrainingAttendance::firstOrNew(
                [
                    'training_id' => $reservation->training_id,
                    'training_attendance_date' => $reservation->reservation_date,
                    'training_attendance_time_id' => $reservation->reservation_time_id,
                ]
            );

            $attendance->save();

            foreach ($reservation->reservation_users as $user) {
                $participants = new TrainingAttendanceUser();
                $participants->reservation_id = $reservation->id;
                $participants->identity_id = $user->user_identity_id;
                $participants->user_log = Auth::user()->username;

                $attendance->participants()->save($participants);
            }

            DB::commit();

            Alert::success('Generate attendance success', 'success');

            return redirect()->route('reservations');
        }
        catch(QueryException $e) {
            DB::rollback();

            return $e->getMessage();
        }

    }

}
