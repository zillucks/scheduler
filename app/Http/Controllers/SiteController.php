<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Alert;
use App\Models\Site;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SitesExport;
use App\Imports\SitesImport;

class SiteController extends Controller
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
        $data['sites'] = Site::filter($request)->orderBy('created_at', 'desc')->paginate(20);
        $data['sites']->appends($request->query());
        
        return view('sites.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.create');
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
                'site_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try {
            $site = new Site();
            $site->site_name = $request->input('site_name');
            $site->user_log = Auth::user()->username;
            $site->save();

            Alert::success('Data Saved', 'Success');
            return redirect()->route('sites');
        }
        catch (QueryException $e) {
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
        return "view {$slug}";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $data['site'] = Site::findBySlug($slug);

        return view('sites.edit', $data);
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
                'site_name' => 'required'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        try{
            $site = Site::findBySlug($slug);

            $site->site_name = $request->input('site_name');
            $site->site_status = $request->has('site_status') ? $request->input('site_status') : false;
            $site->save();
            DB::commit();

            Alert::success('Data Updated', 'Success');
            return redirect()->route('sites');
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
            $site = Site::findBySlug($slug);
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
                $import = new SitesImport();
                $import->import($request->file('files'));

                Alert::success('Import data success', 'Success')->autoclose(3000);

                return redirect()->route('sites.import');
            }
            catch(QueryException $e) {
                Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
                return redirect()->back();
            }
        }
        
        return view('sites.import', $data);
    }

    public function downloadTemplate($type)
    {
        switch ($type) {
            case 'xlsx':
            default:
                $filename = "site-template.{$type}";
                return (new SitesExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
            break;
            case 'xls':
                $filename = "site-template.{$type}";
                return (new SitesExport)->download($filename, \Maatwebsite\Excel\Excel::XLS);
            break;
            case 'csv':
                $filename = "site-template.{$type}";
                return (new SitesExport)->download($filename, \Maatwebsite\Excel\Excel::CSV);
            break;
        }
    }

}
