<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Department;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DepartmentsExport;
use App\Imports\DepartmentsImport;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['departments'] = Department::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['departments']->appends($request->query());
        
        return view('departments.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
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
                'department_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {
            $department = new Department();
            $department->department_name = $request->input('department_name');
            $department->user_log = Auth::user()->username;
            $department->save();

            Alert::success('Data Saved', 'Seccess');
            return redirect()->route('departments');
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
        $data['department'] = Department::findBySLug($slug);

        return view('departments.edit', $data);
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
                'department_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try{
            $site = Department::findBySlug($slug);

            $site->department_name = $request->input('department_name');
            $site->department_status = $request->has('department_status') ? $request->input('department_status') : false;
            $site->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('departments');
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

        DB::beginTransaction();
        try {
            $department = Department::findBySlug($slug);
            $department->delete();

            DB::commit();

            Alert::success('Data Deleted', 'Success');

            $response = [
                'status' => 'deleted',
                'statusCode' => 200,
                'message' => 'Data Deleted'
            ];

            return response()->json($response);
        }
        catch(QueryException $e) {
            DB::rollback();
            Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);

            $response = [
                'code' => $e->getCode(),
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return response()->json($response);
        }
    }

    public function import(Request $request)
    {
        $data = [];
        if ($request->hasFile('files')) {
            try {
                $import = new DepartmentsImport();
                $import->import($request->file('files'));

                Alert::success('Import data success', 'Success')->autoclose(3000);

                return redirect()->route('departments.import');
            }
            catch(QueryException $e) {
                Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
                return redirect()->back();
            }
        }
        
        return view('departments.import', $data);
    }

    public function downloadTemplate($type)
    {
        switch ($type) {
            case 'xlsx':
            default:
                $filename = "department-template.{$type}";
                return (new DepartmentsExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
            break;
            case 'xls':
                $filename = "department-template.{$type}";
                return (new DepartmentsExport)->download($filename, \Maatwebsite\Excel\Excel::XLS);
            break;
            case 'csv':
                $filename = "department-template.{$type}";
                return (new DepartmentsExport)->download($filename, \Maatwebsite\Excel\Excel::CSV);
            break;
        }
    }
}
