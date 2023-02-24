<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Hash;
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
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
        $this->middleware('guest')->except('reset');
    }

    public function reset(Request $request)
    {
        $info = $request->all();
        $validator = Validator::make($info, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6|same:password_confirmation',
        ]);
        if ($validator->fails()) {
            return view('auth.passwords.reset', [
                'error' => 'Mật khẩu phải chứa ít nhất 6 ký tự và khớp nhau !',
                'token' => $info['token'],
                'email' => $info['email']
            ]);
        }
        User::query()->where('email', $request->get('email'))
                     ->update([ 'password' => Hash::make($request->get('password')),
                                'remember_token' => Str::random(60)]);
        return view('auth.passwords.reset', [
            'status' => 'Thay đổi mật khẩu thành công !',
            'token' => $info['token'],
            'email' => $info['email']
        ]);
    }
}
