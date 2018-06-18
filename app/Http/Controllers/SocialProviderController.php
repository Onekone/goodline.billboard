<?php

namespace App\Http\Controllers;

use App\SocialProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\vkontakte\Provider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SocialProviderController extends Controller
{


    //
    public function redirectToProvider()
    {
        return Socialite::with('vkontakte')->redirect();
    }

    //
    public function handleProviderCallback()
    {

        $userSocial = Socialite::driver('vkontakte')->user();
        $accessTokenResponseBody = $userSocial->accessTokenResponseBody;

        // Martin Bean - Stack Overflow
        // https://stackoverflow.com/questions/29127330/laravel-5-socialite-change-auth-redirect-path-dynamically

        try {
            // Try and find user by their social profile UID
            $appUser = SocialProvider::where('social_id',$userSocial->id)->firstOrFail();
            Auth::loginUsingId($appUser->user_id);
            return redirect()->route('user',$appUser->user_id);
        } catch (ModelNotFoundException $e) {
            if (Auth::user()) {
                // Attach social profile to logged in user
                $newSignUp = SocialProvider::create([
                    'user_id' => Auth::id(),
                    'social_id' => $userSocial->id,
                    'social_provider' => 0
                ]);
                return redirect()->route('user',Auth::id());
            } else {
                // User is not logged in, and account does not exist
                // Prompt to register
                return redirect()->route('register.vk', [
                    'social_id' => $userSocial->id,
                    'email' => $userSocial->accessTokenResponseBody['email'],
                    'name' => $userSocial->name,
                ]);
            }
        }

/*
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
                else {
                    return redirect()->route('ad.index');
                }

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
    */
        abort(500);
    }
}
