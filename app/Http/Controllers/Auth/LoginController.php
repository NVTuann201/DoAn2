<?php

namespace App\Http\Controllers\Auth;

use App\DarwinCoreTaxon;
use App\DatasetResource;
use App\Http\Controllers\Controller;
use App\ProtectedArea;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use DB;

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
    protected $redirectTo = '/admin';

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
        return view('admin.auth.login');
    }

    public function index()
    {
        $soloai = DarwinCoreTaxon::query()->distinct('scientific_name')->count();
        $sokhubaoton = ProtectedArea::query()->count();
        $sobodulieu = DatasetResource::query()->filterStatusPublic()->count();

        return view('auth.login', [
            'soloai' => $soloai,
            'sokhubaoton' => $sokhubaoton,
            'sobodulieu' => $sobodulieu,
            'lang' => Session::get('locale'),
        ]);
    }

    public function username()
    {
        return 'username';
    }

    public function getLangLgoin()
    {
        $data = [
            'title' => __('nbds_lang.submenu_species'),
            'message_please_login' => __('nbds_lang.message_please_login'),
            'message_logged_in_as' => __('nbds_lang.message_logged_in_as'),
            'message_registration' => __('nbds_lang.message_registration'),
            'message_welcome_member' => __('nbds_lang.message_welcome_member'),
            'message_email_forgot_password' => __('nbds_lang.message_email_forgot_password'),
            'message_activation_email_again' => __('nbds_lang.message_activation_email_again'),
            'message_reset_password' => __('nbds_lang.message_reset_password'),
            'message_change_password' => __('nbds_lang.message_change_password'),
            'message_change_email_for_auth' => __('nbds_lang.message_change_email_for_auth'),
            'message_unregister' => __('nbds_lang.message_unregister'),
            'email' => __('nbds_lang.organization.email'),
            'password' => __('nbds_lang.users.password'),

        ];
        return response()->json([
            'message' => 'Thành công',
            'result' => $data,
        ], 200, []);
    }

    public function login(Request $request)
    {
        $info = $request->all();
        $getId = \App\Role::query()->select('id')->whereCode('ND2019')->first();

        if (Auth::attempt(array(
            'username' => $info['username'],
            'password' => $info['password']
        ))) {


            DB::beginTransaction();
            try {
                $user = Auth::user();
                if (!$user->isApprove) {
                    Auth::guard()->logout();
                    return view('admin.auth.login', [
                        'error' => 'Tài khoản chưa được hệ thống phê duyệt.'
                    ]);
                }

                createLog($user->id,'login',$request->ip(),$request->header('User-Agent'),'Đã đăng nhập');

                // \App\UserLog::create([
                //     'event_time' => \Carbon\Carbon::now(),
                //     'user_id' => $user->id,
                //     'action' => 'login',
                //     'ip_address' => $request->ip(),
                //     'content' => $request->header('User-Agent'),
                // ]);
                DB::commit();
                return redirect()->route('admin.home');
            } catch (\Exception $ex) {
                DB::rollback();
                \Log::error($ex);
                return view('admin.auth.login', [
                    'error' => 'Không thể đăng nhập. Lỗi hệ thống.'
                ]);
            }
        } else {
            return view('admin.auth.login', [
                'error' => 'Không thể đăng nhập. Tài khoản hoặc mật khẩu không chính xác.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            createLog($user->id,'logout',$request->ip(),$request->header('User-Agent'),'Đã đăng xuất');
            // \App\UserLog::create([
            //     'event_time' => \Carbon\Carbon::now(),
            //     'user_id' => $user->id,
            //     'action' => 'logout',
            //     'ip_address' => $request->ip(),
            //     'content' => $request->header('User-Agent'),
            // ]);
            DB::commit();
            Auth::guard()->logout();

            $request->session()->invalidate();

            return redirect('/');
        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            return redirect()->back();
        }
    }
}
