<?php

namespace App\Http\Controllers;

use App\SocialProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\vkontakte\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SocialProviderController extends Controller
{
    //
    public function redirectToProvider()
    {
        return Socialite::with('vkontakte')->redirect();
    }

    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('vkontakte')->user();
        $accessTokenResponseBody = $userSocial->accessTokenResponseBody;

        $user_exists = User::where('email',$accessTokenResponseBody['email'])->first();

        $auth = Auth::id();
        $authUser = Auth::user();

        if(!$user_exists) {
            // sign up
            $newUser = User::create([
                'name' => $userSocial->name,
                'email' => $accessTokenResponseBody['email'],
                'password' => '',
                'verified' => 1,
            ]);

            $newSignUp = SocialProvider::create([
                'user_id' => $newUser->id,
                'social_id' => $userSocial->id,
                'social_provider' => 0
            ]);

            Auth::loginUsingId($newUser->id, TRUE);
            return redirect()->route('user',$newUser->id);
        }
        else {
            // if not auth
            if (!$auth) {
                // sign in if exists
                if (SocialProvider::where('user_id',$user_exists->id)->first()) {
                    Auth::loginUsingId($user_exists->id, TRUE);
                    return redirect()->route('user',$user_exists->id);
                }
                // else to index
                else
                    return redirect()->route('ad.index');
            }
            else {
            // else
                // connect to vk
                $user_exists->verified = 1;
                $newSignUp = SocialProvider::create([
                    'user_id' => $auth,
                    'social_id' => $userSocial->id,
                    'social_provider' => 0
                ]);

                return redirect()->route('user',$user_exists->id);
            }
        }

        abort(500);
    }
}
