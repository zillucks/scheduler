<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\AvailableTime;
use Validator;
use Carbon\Carbon;

class AvailableTimeController extends Controller
{

    public function __construct(Request $request)
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['available_times'] = AvailableTime::orderBy('start_time', 'asc')->paginate(20);

        return view('available-times.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('available-times.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start_time = new Carbon($request->input('start_time'));
        $end_time = new Carbon($request->input('end_time'));

        $start_time->format('H:i:s');
        $end_time->format('H:i:s');

        $request->merge(['start_time' => $start_time->toTimeString()]);
        $request->merge(['end_time' => $end_time->toTimeString()]);

        $validated = Validator::make(
            $request->input(),
            [
                'start_time' => 'required|date_format:H:i:s|unique:available_time',
                'end_time' => 'required||date_format:H:i:s|after:start_time|unique:available_time',
            ]
        );

        $validated->after(function ($validated) use ($request) {
            $check_current = AvailableTime::where([
                ['start_time', $request->input('start_time')],
                ['end_time', $request->input('end_time')]
            ])->first();

            if($check_current) {
                $validated->errors()->add('start_time', 'Data Exists');
            }
        });

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        try {
            $time = new AvailableTime();
            $time->start_time = $request->input('start_time');
            $time->end_time = $request->input('end_time');

            $time->save();

            Alert::success('Data Saved', 'Success');

            return redirect()->route('available-times');
        }
        catch (QueryException $e) {
            Alert::warning("{$e->getMessage}", "Error {$e->getCode}")->autoclose(5000);
            return redirect()->back();
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
    public function edit($id)
    {
        $data['time'] = AvailableTime::find($id);

        return view('available-times.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $start_time = new Carbon($request->input('start_time'));
        $end_time = new Carbon($request->input('end_time'));

        $start_time->format('H:i:s');
        $end_time->format('H:i:s');

        $request->merge(['start_time' => $start_time->toTimeString()]);
        $request->merge(['end_time' => $end_time->toTimeString()]);

        $validated = Validator::make(
            $request->input(),
            [
                'start_time' => 'required|date_format:H:i:s|unique:available_time,start_time,' . $id,
                'end_time' => 'required|date_format:H:i:s|after:start_time|unique:available_time,end_time,' . $id,
            ]
        );

        $validated->after(function ($validated) use ($request, $id) {
            $check_current = AvailableTime::where([
                ['id', '<>', $id],
                ['start_time', $request->input('start_time')],
                ['end_time', $request->input('end_time')]
            ])->first();

            if($check_current) {
                $validated->errors()->add('start_time', 'Data Exists');
            }
        });

        if ($validated->fails()) {

            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {

            $time = AvailableTime::find($id);
            $time->start_time = $request->input('start_time');
            $time->end_time = $request->input('end_time');

            $time->save();

            Alert::success('Data Updated', 'Success');

            return redirect()->route('available-times');
        }
        catch (QueryException $e) {
            Alert::warning("{$e->getMessage}", "Error {$e->getCode}")->autoclose(5000);
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            AvailableTime::destroy($id);

            Alert::error('Data Deleted', 'Deleted');

            return response()->json([
                'code' => 200,
                'status' => 'success',
                'message' => 'Data Deleted'
            ]);
        }
        catch(QueryException $e) {
            Alert::error("{$e->getMessage()}", "Error {$e->getCode()}");

            return response()->json([
                'code' => $e->getCode(),
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
