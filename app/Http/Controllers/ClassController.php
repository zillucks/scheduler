<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Classes;
use App\Models\Site;
use App\Models\Reservation;
use App\Models\Training;
use Carbon\Carbon;
use Validator;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['classes'] = Classes::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['classes']->appends($request->query());
        
        return view('classes.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['sites'] = Site::where('site_status', true)->orderBy('slug', 'asc')->pluck('site_name', 'id');
        return view('classes.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = Validator::make(
            $request->input(),
            [
                'class_name' => 'required',
                'site_id' => 'required',
                'max_quotes' => 'required|integer|min:0'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $class = new Classes();
            
            $class->class_name = $request->input('class_name');
            $class->site_id = $request->input('site_id');
            $class->max_quotes = $request->input('max_quotes');
            $class->user_log = Auth::user()->username;

            $class->save();
            DB::commit();

            Alert::success('Data Saved', 'Success');
            return redirect()->route('classes');
        }
        catch (QueryException $e) {
            DB::rollback();
            Alert::error($e->getMessage(), "Error {$e->getCode()}");

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $data['class'] = Classes::findBySLug($slug);
        $data['sites'] = Site::orderBy('slug', 'asc')->pluck('site_name', 'id');

        return view('classes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $validated = Validator::make(
            $request->input(),
            [
                'class_name' => 'required',
                'site_id' => 'required',
                'max_quotes' => 'required|integer|min:0'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        DB::beginTransaction();

        try{
            $class = Classes::findBySlug($slug);

            $class->class_name = $request->input('class_name');
            $class->site_id = $request->input('site_id');
            $class->max_quotes = $request->input('max_quotes');
            $class->class_status = $request->has('class_status') ? $request->input('class_status') : false;
            $class->user_log = Auth::user()->username;

            $class->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('classes');
        }
        catch(QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), "Error {$e->getCode()}");
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $slug)
    {
        if (!$request->isMethod('delete')) {
            if ($request->ajax()) {
                Alert::error("Method Not Allowed", "Error 405");
                $response = [
                    'status' => 'failed',
                    'statusCode' => 405,
                    'message' => 'Method Not Allowed'
                ];
                return response()->json($response, 405);
            }
            else {
                Alert::error("Method Not Allowed", "Error 405");
                return redirect()->back();
            }
        }

        try {
            $class = Classes::findBySlug($slug);
            $class->delete();

            Alert::success('Data Deleted', 'Success');
            
            $response = [
                'status' => 'deleted',
                'statusCode' => 200,
                'message' => 'Data Deleted'
            ];

            return response()->json($response);
        }
        catch(QueryException $e) {
            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);

            $response = [
                'code' => $e->getCode(),
                'status' => 'error',
                'message' => $e->getMessage
            ];

            return response()->json($response);
        }
    }

    public function reRegistration(Request $request)
    {
        $date = Carbon::now();
        $data['trainings'] = Training::whereHas('reservations', function ($reservation) use($date) {
            $reservation
                ->where('reservation_status', '<>', 'declined')
                ->whereDate('reservation_date', '=', $date->toDateString());
        })->with([
            'choosen_times' => function ($times) {
                $times->orderBy('start_time', 'asc');
            }
        ])
        ->paginate(20);
        $data['trainings']->appends($request->query());

        return view('classes.re-registration', $data);
    }

    public function checkin(Request $request, $training_id, $time_id)
    {
        $data['trainings'] = Training::whereHas('reservations', function ($reservation) use($time_id) {
            $date = Carbon::now();
            $reservation
                ->where([
                    ['reservation_time_id', $time_id],
                    ['reservation_status', '<>', 'declined']
                ])
                ->whereDate('reservation_date', $date->format('Y-m-d'));
        })->find($training_id);

        return $data;

        return view('classes.checkin', $data);
    }
}
