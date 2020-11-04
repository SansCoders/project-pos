<?php

namespace App\Http\Controllers\Auth;

use App\AboutUs;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{

    use AuthenticatesUsers;

    // protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:cashier')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
    public function showAdminLoginForm()
    {
        $bgauth_top = "bg-gradient-teal";
        return view('auth.login', ['url' => 'admin', 'bgauth_top' => $bgauth_top]);
    }

    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required|min:3',
            'password' => 'required|min:6'
        ], [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min huruf'
        ]);

        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/admin');
        }
        return back()->withInput($request->only('username'))->withErrors(['Cek kembali username dan passwordnya']);
    }

    public function showCashierLoginForm()
    {
        $bgauth_top = "bg-gradient-warning";
        return view('auth.login', ['url' => 'cashier', 'bgauth_top' => $bgauth_top]);
    }

    public function cashierLogin(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required|min:3',
            'password' => 'required|min:6'
        ], [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min huruf'
        ]);

        if (Auth::guard('cashier')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/cashier');
        }
        return back()->withInput($request->only('username'))->withErrors(['Cek kembali username dan passwordnya']);
    }
}
