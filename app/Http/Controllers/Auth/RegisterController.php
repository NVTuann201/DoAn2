<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Redirect;
use App\Traits\GetDataCache;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, GetDataCache;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'min:4|required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:6|same:password_confirmation',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $info = $request->all();
        $getId = Role::query()->select('id')->whereCode('ND2019')->first();
        $validate = $this->validator($info);
        if($validate->fails()) {
            return view('admin.auth.register',[
                'error' => $validate->errors()->messages()
            ]);
        }
        else{
            User::create([
                'name' => $info['name'],
                'username' => $info['email'],
                'email' => $info['email'],
                'password' => bcrypt($info['password']),
                'role_id' => $getId->id,
                'inactive' => false,
                'email_token' => str_random(20)
            ]);
            return view('admin.auth.login', [
                'success' => 'Đăng kí thành công. Vui lòng kiểm tra email để xác nhận tài khoản'
            ]);
        }
    }

    public function verifyEmail($token)
    {
        $getId = User::query()->where('email_token',$token)->first();
        $getId->update(['inactive' => false]);
        return view('emails.verify');
    }



    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }
}
