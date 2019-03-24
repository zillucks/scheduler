<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use Carbon\Carbon;
use Mail;
use App\Jobs\SendAttendanceMail;
use App\Mail\ParticipantPresence;
use App\Mail\ParticipantPresenceManager;
use App\Models\TrainingAttendance;
use App\Models\TrainingAttendanceUser;
use Validator;
use PDF;

class AttendanceController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }

    public function reRegistration(Request $request)
    {
        $date = Carbon::now();
        $data['attendances'] = TrainingAttendance::filter($request)->whereDate('training_attendance_date', $date->toDateString())
            ->paginate(20);
        $data['attendances']->appends($request->query());

        return view('attendances.re-registration', $data);
    }

    public function checkin(Request $request, $id)
    {
        $data['attendance'] = TrainingAttendance::find($id);

        return view('attendances.checkin', $data);
    }

    public function checkinPresence(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->input('attendances') as $key => $attendances) {
                $participant = TrainingAttendanceUser::find($attendances);
                $participant->training_attendance_user_status = $request->input('presence');
                $participant->save();
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Cek Kehadiran Sukses'
            ]);

        }
        catch(QueryException $e) {
            DB::rollback();

            return response()->json([
                'code' => 500,
                'status' => "error {$e->getCode()}",
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkinSubmit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $attendance = TrainingAttendance::find($id);
            $attendance->training_attendance_status = true;
            $attendance->save();

            foreach ($attendance->participants as $participant) {
                $identity = $participant->identity;
                $reservation = $participant->reservation;
                $reservation->reservation_status = 'approved';
                $reservation->save();
                
                // Mail::to($participant->reservation->manager_email)->send(new ParticipantPresenceManager($attendance, $participant));
                // Mail::to($participant->identity->email)->send(new ParticipantPresence($attendance, $participant));


                // SendAttendanceMail::dispatch($identity->email, $attendance, $participant, $identity, 'user');
                // SendAttendanceMail::dispatch($participant->reservation->manager_email, $attendance, $participant, $identity, 'manager');
            }
            
            DB::commit();

            Alert::success('Presensi Training Selesai', 'Success')->autoclose(3000);

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Presensi Selesai'
            ]);
        }
        catch(QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);

            return response()->json([
                'code' => 500,
                'status' => 'error ' . $e->getCode(),
                'message' => $e->getMessage
            ]);
        }
    }
}
