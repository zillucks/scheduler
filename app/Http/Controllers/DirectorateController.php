<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Directorate;
use Validator;

class DirectorateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['directorates'] = Directorate::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['directorates']->appends($request->query());
        
        return view('directorates.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('directorates.create');
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
                'directorate_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {
            $directorate = new Directorate();
            $directorate->directorate_name = $request->input('directorate_name');
            $directorate->user_log = Auth::user()->username;
            $directorate->save();

            Alert::success('Data Saved', 'Seccess');
            return redirect()->route('directorates');
        }
        catch(QueryException $e) {
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
        $data['directorate'] = Directorate::findBySLug($slug);

        return view('directorates.edit', $data);
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
                'directorate_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try{
            $site = Directorate::findBySlug($slug);

            $site->directorate_name = $request->input('directorate_name');
            $site->directorate_status = $request->has('directorate_status') ? $request->input('directorate_status') : false;
            $site->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('directorates');
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
                return response($response, 405);
            }
            else {
                Alert::error("Method Not Allowed", "Error 405");
                return redirect()->back();
            }
        }

        try {
            $site = Directorate::findBySlug($slug);
            $site->delete();

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
                'status' => 'failed',
                'statusCode' => 405,
                'message' => $e->getMessage
            ];

            return response()->json($response);
        }
    }
}
