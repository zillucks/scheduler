<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use Alert;
use App\Models\User;
use App\Models\Department;
use App\Models\Directorate;
use App\Models\Organization;
use App\Models\Identity;
use App\Models\Role;
use App\Models\Site;
use App\Models\Unit;
use Validator;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class UserController extends Controller
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
        $data['users'] = User::orderBy('username', 'asc')->filter($request)->with('identity')->orderBy('created_at', 'desc')->paginate(20);
        $data['users']->appends($request->query());

        // return $data['users'];
        
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['departments'] = Department::orderBy('slug', 'asc')->pluck('department_name', 'id');
        $data['directorates'] = Directorate::orderBy('slug', 'asc')->pluck('directorate_name', 'id');
        $data['organizations'] = Organization::orderBy('slug', 'asc')->pluck('organization_name', 'id');
        $data['sites'] = Site::orderBy('slug', 'asc')->pluck('site_name', 'id');
        $data['units'] = Unit::orderBy('slug', 'asc')->pluck('unit_name', 'id');
        return view('users.create', $data);
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
                'username' => 'required|unique:users',
                'email' => 'nullable|unique:users'
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        DB::beginTransaction();        
        try {
            $user = new User();
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->password = $request->has('password') ? $request->input('password') : null;

            $user->save();

            $identity = new Identity();
            $identity->full_name = $request->input('full_name');
            $identity->email = $request->input('email');
            $identity->department_id = $request->input('department_id');
            $identity->directorate_id = $request->input('directorate_id');
            $identity->organization_id = $request->input('organization_id');
            $identity->site_id = $request->input('site_id');
            $identity->unit_id = $request->input('unit_id');
            $identity->identity_status = $request->has('identity_status') ? $request->input('identity_status') : false;

            $user->identity()->save($identity);

            DB::commit();

            Alert::success('Data Saved', 'Success')->autoclose(5000);
            return redirect()->route('users');
        }
        catch (QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), 'Error ' . $e->getCode)->autoclose(5000);

            return redirect()->back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
    {
        $data['user'] = User::with('identity')->where('username', $username)->first();
        
        $data['departments'] = Department::orderBy('slug', 'asc')->pluck('department_name', 'id');
        $data['directorates'] = Directorate::orderBy('slug', 'asc')->pluck('directorate_name', 'id');
        $data['organizations'] = Organization::orderBy('slug', 'asc')->pluck('organization_name', 'id');
        $data['sites'] = Site::orderBy('slug', 'asc')->pluck('site_name', 'id');
        $data['units'] = Unit::orderBy('slug', 'asc')->pluck('unit_name', 'id');

        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        $user = User::findByUsername($username);
        
        $validated = Validator::make(
            $request->input(),
            [
                'username' => [
                    'required',
                    Rule::unique('users')->ignore($user->id)
                ],
                'email' => [
                    Rule::unique('users')->ignore($user->id)
                ]
            ]
        );

        if ($validated->fails()) {
            return redirect()->back()
                ->withErrors($validated)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $user->email = $request->input('email');

            $identity = Identity::firstOrNew(['user_id' => $user->id]);
            $identity->full_name = $request->input('full_name');
            $identity->email = $request->input('email');
            $identity->department_id = $request->input('department_id');
            $identity->directorate_id = $request->input('directorate_id');
            $identity->organization_id = $request->input('organization_id');
            $identity->site_id = $request->input('site_id');
            $identity->unit_id = $request->input('unit_id');
            $identity->identity_status = $request->has('identity_status') ? $request->input('identity_status') : false;

            $user->save();
            $user->identity()->save($identity);

            DB::commit();

            Alert::success('Data Updated', 'Success')->autoclose(5000);

            return redirect()->route('users');
        }
        catch (QueryException $e) {
            DB::rollback();

            Alert::error($e->getMessage(), 'Error ' . $e->getCode)->autoclose(5000);

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $username)
    {
        if (!$request->isMethod('delete')) {
            if ($request->ajax()) {
                Alert::error('Method Not Allowed', 'Error 405!');

                $response = [
                    'status' => 'failed',
                    'statusCode' => 405,
                    'message' => 'Method Not Allowed'
                ];

                return response($response, 405);
            }
            else {
                Alert::error('Method Not Allowed', 'Error 405!');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $user = User::findByUsername($username);
            $user->identity()->delete();
            $user->delete();

            DB::commit();

            Alert::warning('Data Deleted', 'Deleted');

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
                'status' => 'failed',
                'statusCode' => 405,
                'message' => $e->getMessage()
            ];

            return response()->json($response);
        }
    }

    public function setting(Request $request, $username)
    {
        $data['user'] = User::findByUsername($username);
        $data['role_lists'] = Role::orderBy('slug', 'asc')->pluck('role_name', 'id');

        return view('users.setting', $data);
    }

    public function settingSubmit(Request $request, $username)
    {
        if (!$request->isMethod('post')) {
            Alert::error('Method not allowed', 'Error 405')->autoclose(3000);

            return redirect()->route('users');
        }

        $validated = Validator::make(
            $request->input(),
            [
                'password' => 'confirmed'
            ]
        );

        if ($validated->fails()) {
            Alert::error($validator->errors()->first(), 'error')->autoclose(false);
            return redirect()->back()->withError($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::findByUsername($username);
            $user->password = $request->input('password');
            $user->save();

            $user->roles()->sync($request->input('role_id'));

            DB::commit();

            Alert::success('Data Updated', 'success')->autoclose(3000);

            return redirect()->route('users');

        }
        catch(QueryException $e) {
            DB::rollback();
            Alert::error($e->getMessage(), "Error {$e->getCode()}");
            return redirect()->back()->withInput();
        }

    }

    public function profile(Request $request)
    {
        $data['user'] = Auth::user();
        return view('users.profile', $data);
    }

    public function profileUpdate(Request $request)
    {
        if (!$request->isMethod('PUT')) {
            Alert::error('Method not allowed', 'Error 405');
            return redirect()->back();
        }

        $identity = Auth::user()->identity;

        $validated = Validator::make(
            $request->input(),
            [
                'full_name' => 'required',
                'email' => 'required|email|unique:identity,email,' . $identity->id
            ]
        );

        if ($validated->fails()) {
            Alert::error($validated->errors()->first());
            return redirect()->back()->withError($validated)->withInput();
        }

        try {
            $identity->full_name = $request->input('full_name');
            $identity->email = $request->input('email');
            Auth::user()->identity()->save($identity);

            Alert::success('Date Upated', 'Success')->autoclose(3000);
            return redirect()->route('profile');
        }
        catch(QueryException $e) {
            Alert::error($e->getMessage, "Error {$e->getCode()}")->autoclose(false);
            return redirect()->back()->withInput();
        }

    }

    public function import(Request $request)
    {
        $data = [];
        $user = Auth::user();

        if ($request->isMethod('post')) {
            if ($request->hasFile('files')) {
                try {
                    $import = new UsersImport();
                    $import->import($request->file('files'));

                    Alert::success('Import data success', 'Success')->autoclose(3000);

                    return redirect()->route('users');
                }
                catch(QueryException $e) {
                    Alert::error($e->getMessage(), "Error {$e->getCode()}")->autoclose(false);
                    return redirect()->back();
                }
            }
        }

        return view('users.import');
    }

    public function downloadTemplate($type)
    {
        switch ($type) {
            case 'xlsx':
            default:
                $filename = "user-template.{$type}";
                return (new UsersExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
            break;
            case 'xls':
                $filename = "user-template.{$type}";
                return (new UsersExport)->download($filename, \Maatwebsite\Excel\Excel::XLS);
            break;
            case 'csv':
                $filename = "user-template.{$type}";
                return (new UsersExport)->download($filename, \Maatwebsite\Excel\Excel::CSV);
            break;
        }
    }

}
