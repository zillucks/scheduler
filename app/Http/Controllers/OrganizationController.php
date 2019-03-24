<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Organization;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrganizationsExport;
use App\Imports\OrganizationsImport;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['organizations'] = Organization::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['organizations']->appends($request->query());
        
        return view('organizations.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('organizations.create');
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
                'organization_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {
            $organization = new Organization();
            $organization->organization_name = $request->input('organization_name');
            $organization->user_log = Auth::user()->username;
            $organization->save();

            Alert::success('Data Saved', 'Success');
            return redirect()->route('organizations');
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
        $data['organization'] = Organization::findBySLug($slug);

        return view('organizations.edit', $data);
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
                'organization_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try{
            $site = Organization::findBySlug($slug);

            $site->organization_name = $request->input('organization_name');
            $site->organization_status = $request->has('organization_status') ? $request->input('organization_status') : false;
            $site->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('organizations');
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
            $organization = Organization::findBySlug($slug);
            $organization->delete();

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
                $import = new OrganizationsImport();
                $import->import($request->file('files'));

                Alert::success('Import data success', 'Success')->autoclose(3000);

                return redirect()->route('organizations.import');
            }
            catch(QueryException $e) {
                Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
                return redirect()->back();
            }
        }
        
        return view('organizations.import', $data);
    }

    public function downloadTemplate($type)
    {
        switch ($type) {
            case 'xlsx':
            default:
                $filename = "organization-template.{$type}";
                return (new OrganizationsExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
            break;
            case 'xls':
                $filename = "organization-template.{$type}";
                return (new OrganizationsExport)->download($filename, \Maatwebsite\Excel\Excel::XLS);
            break;
            case 'csv':
                $filename = "organization-template.{$type}";
                return (new OrganizationsExport)->download($filename, \Maatwebsite\Excel\Excel::CSV);
            break;
        }
    }
}
