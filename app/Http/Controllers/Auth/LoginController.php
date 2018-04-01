<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use App\User;
use Validator;
use Auth;
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

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $user = User::whereEmail($request->email)->withTrashed()->first();

        if ($user and $user->deleted_at == NULL) {
            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->user_role_id != 5) {
                    if ($user->is_blocked == 0) {
                        if (Auth::attempt(['email' => $request->email,'password' => $request->password])) {
                            if (Auth::check()) {/*var_dump("test");dd();*/
                                return redirect('home');
                            } else {
                                return redirect('login')->with('error', 'Something goes wrong. Please try again!');
                            }
                        } else {
                            return redirect()->back()->withErrors(['password' => 'Credential dos\'nt match!']);
                        }
                    } else {
                        return redirect('login')->with('error', 'Your Account has been blocked by Admin. Please contact Admin to unblock your account.');
                    }
                } else {
                    return redirect('login')->with('error', 'You are not authorized to access!');
                }
            } else {
                return redirect()->back()->withErrors(['password' => 'Please enter a Valid Password']);
            }
        } elseif ($user and $user->deleted_at != NULL) {
            return redirect('login')->with('error', 'Your account is unavailable. Please create another account using different email id to continue.');
        } else {
            return redirect()->back()->withErrors(['email' => 'Email does not match']);
        }
    }
}
