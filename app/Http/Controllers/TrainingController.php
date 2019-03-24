<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Alert;
use App\Models\AvailableTime;
use App\Models\Classes;
use App\Models\Site;
use App\Models\Training;
use App\Models\TrainingAttendance;
use App\Models\TrainingAvailableTime;
use Validator;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['trainings'] = Training::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['trainings']->appends($request->query());
        
        return view('trainings.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['sites'] = Site::where('site_status', true)->orderBy('slug', 'asc')->pluck('site_name', 'id');
        $data['classes'] = Classes::where('class_status', true)->orderBy('slug', 'asc')->get()->pluck('class_name', 'id');
        $data['times'] = AvailableTime::orderBy('start_time', 'asc')->get();

        return view('trainings.create', $data);
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
                'training_name' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'site_id' => 'required',
                'class_id' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $training = new Training();
            $training->training_name = $request->input('training_name');
            $training->site_id = $request->input('site_id');
            $training->class_id = $request->input('class_id');
            $training->start_date = $request->input('start_date');
            $training->end_date = $request->input('end_date');
            $training->user_log = Auth::user()->username;

            if ($request->has('training_status')) {
                $training->training_status = $request->input('training_status');
            }

            $training->save();

            if ($request->has('training_available_time')) {
                foreach ($request->input('training_available_time') as $key => $time) {
                    $available_time = new TrainingAvailableTime();
                    $available_time->available_time_id = $time;
                    $training->times()->save($available_time);
                }
            }

            DB::commit();

            Alert::success("Data Saved", "Success")->autoclose(3000);

            return redirect()->route('trainings');
        }
        catch(QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $data['training'] = Training::findBySlug($slug);

        $data['sites'] = Site::where('site_status', true)->orderBy('slug')->pluck('site_name', 'id');
        $data['classes'] = Classes::where('site_id', $data['training']->site_id)->orderBy('slug')->pluck('class_name', 'id');
        $data['times'] = AvailableTime::orderBy('start_time', 'asc')->get();

        return view('trainings.edit', $data);
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
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'site_id' => 'required',
                'class_id' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        DB::beginTransaction();
        try {
            $training = Training::findBySlug($slug);
            $training->training_name = $request->input('training_name');
            $training->site_id = $request->input('site_id');
            $training->class_id = $request->input('class_id');
            $training->start_date = $request->input('start_date');
            $training->end_date = $request->input('end_date');
            $training->user_log = Auth::user()->username;
            $training->training_status = $request->has('training_status') ? true : false;

            $training->save();

            if ($request->has('training_available_time')) {
                $training->times()->delete();
                foreach ($request->input('training_available_time') as $key => $time) {
                    $available_time = new TrainingAvailableTime();
                    $available_time->available_time_id = $time;
                    $training->times()->save($available_time);
                }
            }

            DB::commit();

            Alert::success('Data Updated', 'Success')->autoclose(3000);

            return redirect()->route('trainings');
        }
        catch(QueryException $e) {
            DB::rollback();
            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
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
            Alert::error("Method not allowed", "Error 405")->autoclose(false);

            return response()->json([
                'code' => 405,
                'status' => 'error',
                'message' => 'Method not allowed'
            ]);
        }

        DB::beginTransaction();
        try {
            $training = Training::findBySlug($slug);
            // $traiing->times()->delete();
            $training->delete();

            DB::commit();

            Alert::success("Data Deleted", "Success");

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Data Deleted'
            ]);
        }
        catch (QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
            
            return response()->json([
                'code' => $e->getCode,
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function schedules(Request $request)
    {
        $data['schedules'] = TrainingAttendance::withCount('participants')->filter($request)->paginate(20);
        $data['schedules']->appends($request->query());

        return view('trainings.schedules', $data);
    }

    public function participants(Request $request, $id)
    {
        $data['participants'] = TrainingAttendance::find($id)->participants;
        
        return view('trainings.participants', $data);
    }

    public function availableTimes()
    {
        $data['available_times'] = AvailableTime::orderBy('start_date', 'asc')->paginate(20);

        return view('trainings.available-times', $data);
    }
}
