<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Unit;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UnitsExport;
use App\Imports\UnitsImport;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['units'] = Unit::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['units']->appends($request->query());
        
        return view('units.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('units.create');
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
                'unit_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {
            $unit = new Unit();
            $unit->unit_name = $request->input('unit_name');
            $unit->user_log = Auth::user()->username;
            $unit->save();

            Alert::success('Data Saved', 'Success');
            return redirect()->route('units');
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
        $data['unit'] = Unit::findBySLug($slug);

        return view('units.edit', $data);
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
                'unit_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try{
            $site = Unit::findBySlug($slug);

            $site->unit_name = $request->input('unit_name');
            $site->unit_status = $request->has('unit_status') ? $request->input('unit_status') : false;
            $site->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('units');
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
            $unit = Unit::findBySlug($slug);
            $unit->delete();

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
                $import = new UnitsImport();
                $import->import($request->file('files'));

                Alert::success('Import data success', 'Success')->autoclose(3000);

                return redirect()->route('units.import');
            }
            catch(QueryException $e) {
                Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
                return redirect()->back();
            }
        }
        
        return view('units.import', $data);
    }

    public function downloadTemplate($type)
    {
        switch ($type) {
            case 'xlsx':
            default:
                $filename = "unit-template.{$type}";
                return (new UnitsExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
            break;
            case 'xls':
                $filename = "unit-template.{$type}";
                return (new UnitsExport)->download($filename, \Maatwebsite\Excel\Excel::XLS);
            break;
            case 'csv':
                $filename = "unit-template.{$type}";
                return (new UnitsExport)->download($filename, \Maatwebsite\Excel\Excel::CSV);
            break;
        }
    }
}
