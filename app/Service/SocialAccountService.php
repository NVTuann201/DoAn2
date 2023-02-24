<?php

namespace App\Service;

use App\SocialAccount;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{

    public static function createOrGetUser(ProviderUser $providerUser, $social, $check)
    {
        if ($social == 'oauth') {
            return SocialAccountService::createOrGetOauthUser($providerUser, $check);
        }
        $account = SocialAccount::whereProvider($social)
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account && $check == 'login') {
            return $account->user;
        } else {
            if ($account) {
                return 'Email này đã được sử dụng. Vui lòng thử lại';
            } else {
                $user = User::whereEmail($providerUser->getEmail())->first();
                if ($user) {
                    return 'Email này đã được sử dụng. Vui lòng thử lại';
                } else {
                    DB::beginTransaction();
                    try {
                        $account = new SocialAccount([
                            'provider_user_id' => $providerUser->getId(),
                            'provider' => $social
                        ]);
                        $getId = Role::query()->select('id')->whereCode('ND2019')->first();

                        $user = User::create([
                            'email' => $providerUser->getEmail(),
                            'name' => $providerUser->getName(),
                            'username' => $providerUser->getEmail(),
                            'inactive' => true,
                            'role_id' => $getId->id,
                            'password' => md5(rand(1, 10000)),
                            'email_token' => str_random(20)
                        ]);
                        $account->user()->associate($user);
                        $account->save();
                        DB::commit();
                        return $user;
                    } catch (\Exception $e) {
                        DB::rollback();
                        dd($e);
                    }
                }
            }
        }
    }

    public static function createOrGetOauthUser(ProviderUser $providerUser, $check)
    {
        $account = SocialAccount::whereProvider('oauth')
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account && $check != 'login') {
            return 'Email này đã được sử dụng. Vui lòng thử lại';
        }
        $user = User::whereEmail($providerUser->getEmail())->first();
        if (!isset($account) && $user) {
            return 'Email này đã được sử dụng. Vui lòng thử lại';
        }

        $role = Role::query()->select('id')->where("code", $providerUser->role_code)->orWhere("code", 'ND2019')->first();
        $userRaw = $providerUser->user;
        if ($account)
            $user = $account->user;
        else {
            $user = new User();
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => 'oauth'
            ]);
            $user->fill([
                'password' => md5(rand(1, 10000)),
                'email_token' => str_random(20),
            ]);
        }
        DB::beginTransaction();
        try {
            $user->fill([
                'email' => $providerUser->getEmail(),
                'name' => $providerUser->getName(),
                'username' => $userRaw['username'],
                'inactive' => true,
                'role_id' => $role->id,
                'phone' => $userRaw['phone'],
                'avatar_url' => $userRaw['avatar_url']
            ]);
            $user->save();
            $account->user()->associate($user);
            $account->save();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return "Lỗi";
        }
    }
}
