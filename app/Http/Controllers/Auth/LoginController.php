<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    /**
     * ログイン時にユーザー情報を返却する
     * AuthenticatesUsers->authenticated をオーバーライド
     */
    protected function authenticated(Request $request, $user)    //追加
    {
        logger('LoginController->authenticated');
        return $user;
    }

    /**
     * ログアウト時処理
     * セッションの再生成を行う
     * AuthenticatesUsers->loggedOut をオーバーライド
     */
    protected function loggedOut(Request $request)
    {
        $request->session()->regenerate();
        return response('', 200);
    }
}
