<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\GetDataCache;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Carbon\Carbon;


class ProfileController extends Controller
{
    use GetDataCache;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data = Auth::user();
        $getImageUrl = $data->getFirstMediaUrl();
        return view('admin.profile', [
            'user' => $data,
            'image' => !empty($getImageUrl) ? $getImageUrl : ''
        ]);
    }

    /**
     * Handle a update profile request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $info = $request->all();

        $user = User::findOrFail($user->id);
        $validator = Validator::make($info, [
            'name' => 'required|max:255',
            'email' => 'email|max:255|unique:users,email,' . $user->id,
            'mobile' => 'regex:/(01)[0-9]{9}/|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => __('message.fails'),
            ], 401, [
                $validator->errors()
            ]);
        }
        if (empty($info['updated_at'])) {
            $info['updated_at'] = now();
        }

        DB::beginTransaction();
        try {
            $user->update($info);
            $user = Auth::user();
            createLog($user->id, 'update_user', $request->ip(), $request->header('User-Agent'), 'Cập nhật user');

            DB::commit();
            return response()->json([
                'message' => __('message.success'),
            ], 200, []);
        } catch (\Exception $exception) {
            DB::rollBack();
            \Log::error($exception);
            return response()->json([
                'message' => __('message.fails'),
            ], 500, []);
        }
    }

    public function changePassword(Request $request)
    {
        $info = $request->all();

        $validator = Validator::make($info, [
            'password' => 'required',
            'new_password' => 'required|max:255|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.invalid'));
        }

        $user = Auth::user();

        if (!Hash::check($info['password'], $user->password)) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.password_wrong'));
        }

        DB::beginTransaction();
        try {
            User::query()
                ->find($user->id)
                ->update(['password' => Hash::make($info['new_password'])]);
            $user = Auth::user();
            createLog($user->id, 'change_password', $request->ip(), $request->header('User-Agent'), 'Thay đổi mật khẩu');

            DB::commit();

            return back()->withInput()
                ->with('alert-type', 'alert-success')
                ->with('alert-content', __('system.update_password_success'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('alert-type', 'alert-warning')
                ->with('alert-content', __('system.update_error'));
        }
    }
}
