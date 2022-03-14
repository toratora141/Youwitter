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
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'account_name';
    }

    public function login(UserLoginRequest $request)
    {
        $result = false;
        $login_message = '';
        $user = [];
        $credentials = $request->only('account_name', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $result = true;
        } else {
            $login_message = 'アカウントIDまたはパスワードが間違っています。';
        }
        return response()->json(['result' => $result, 'login_message' => $login_message, 'account_name' => $request->user()->account_name]);
    }

    public function me(Request $request): JsonResponse
    {
        $result = false;
        $user = [];
        if (Auth::check()) {
            $user = Auth::user();
            $result = true;
        }
        return response()->json(['result' => $result, 'user' => $user]);

        $user = $request->user();

        return new JsonResponse([
            'account_name' => $user->account_name,
            'display_name' => $user->display_name,
        ]);
    }
}
