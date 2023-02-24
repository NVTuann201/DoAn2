<?php

// SocialAuthFacebookController.php

namespace App\Http\Controllers;

use App\Service\SocialAccountService;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */

    public function redirect($action, $social)
    {
        Session::put('check', $action);
        return Socialite::driver($social)->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback($social)
    {
        $check = Session::get('check');
        $user = SocialAccountService::createOrGetUser(Socialite::driver($social)->user(), $social, $check);

        if (!is_string($user)) {
            auth()->login($user);
            return redirect()->to('/');
        } else {
            return redirect()->to('/login');
        }
    }
}
