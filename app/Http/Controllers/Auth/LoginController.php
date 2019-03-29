<?php

namespace App\Http\Controllers\Auth;

use Adldap\Laravel\Facades\Adldap;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Database\QueryException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    // public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     if ($this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     $credentials = $request->only('username', 'password');

    //     if (Auth::guard('ldap')->attempt($credentials)) {
    //         return $this->sendLoginResponse($request);
    //     }
    //     else {
    //         return $this->sendFailedLoginResponse($request);
    //     }

    //     $this->incrementLoginAttempts($request);

    //     return $this->sendFailedLoginResponse($request);
    // }
    
}
