<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }

    public function username()
    {
        return 'account_name';
    }

    public function originalLogin(UserLoginRequest $request)
    {
        $result = false;
        $login_message = '';
        $user = [];
        $param = $request->only('account_name', 'password');
        if (Auth::attempt($param, true)) {
            $request->session()->regenerate();
            $result = true;
            // $param = User::find(1)->prepareUserCookie($param['account_name']);
            $param['result'] = true;
            $param['login_message'] = 'ログインしました！';
            // Cookie::queue(
            //     $name = $remember_cookie_name,
            //     $value = Cookie::queued($remember_cookie_name)->getValue(),
            //     $minutes = 10080, // 7日
            //     $path = '/',
            //     $domain = null
            // );
            // dd(Session::all());
        } else {
            $login_message = 'アカウントIDまたはパスワードが間違っています。';
            return response()->json(['result' => $result, 'login_message' => $login_message]);
        }
        return response()->json($param);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['result' => true]);
    }
}
