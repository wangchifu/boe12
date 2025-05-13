<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Session;

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
    protected $redirectTo = '/login';

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

    public function attemptLogin(Request $request)
    {
        if (Auth::attempt([
            'username' => $request->input('username'),
            'password'=>$request->input('password'),
            //'login_type'=>'local',
            'disable'=>null,
        ])) {
            // 如果認證通過...
            if($request->input('chaptcha') != session('chaptcha')){
                return back()->withErrors(['gsuite_error'=>['驗證碼錯誤！']])
                    ->withInput(Input::all());
            }

            return redirect()->intended('dashboard');
        }else{
            return back()->withErrors(['errors'=>['帳號密碼錯誤']])
                ->withInput(Input::all());
        }
    }

    public function logout(){
        Session::flush();
        Auth::logout();
        return redirect()->route('index');
    }

}
