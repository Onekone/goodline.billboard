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

    //
    public function handleProviderCallback()
    {
        $userSocial = Socialite::driver('vkontakte')->user();
        $accessTokenResponseBody = $userSocial->accessTokenResponseBody;

        $userByEmail = User::where('email',$accessTokenResponseBody['email'])->first();
        $userBySocialID = SocialProvider::where('social_id',$userSocial->id)->first();


        $auth = Auth::user();


        if ($auth) // if logged in
        {
            // connect to vk
            $auth->verified = 1;
            $newSignUp = SocialProvider::create([
                'user_id' => $auth,
                'social_id' => $userSocial->id,
                'social_provider' => 0
            ]);

            return redirect()->route('user',$auth->id);
        }
        else
        {
            // есть ли привзяка
            if ($userBySocialID) {
                // sign in
                Auth::loginUsingId($userBySocialID->user_id, TRUE);
                return redirect()->route('user',$userBySocialID->user_id);
            }
            else {
                if ($accessTokenResponseBody['email']) { // есть почта в ВК
                    if ($userByEmail) { // есть ли аккаунт с такой почтой
                        return redirect()->route('ad.index');
                    }
                    else
                    {
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
                }
                else
                {
                    // нет почты в ВК
                    return view('auth.askemail')->withUserToken($userSocial)->withAccessToken($accessTokenResponseBody);
                }

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
