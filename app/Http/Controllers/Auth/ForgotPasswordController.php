<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function sendResetLinkResponse($response)
    {
        return view('auth.passwords.email', [
            'status' => 'Chúng tôi đã gửi một liên kết đặt lại mật khẩu tới email của bạn !',
        ]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return view('auth.passwords.email', [
            'error' => 'Email không tồn tại. Vui lòng kiểm tra lại !',
        ]);
    }

    public function broker()
    {
        return Password::broker('users');        
    }

}
