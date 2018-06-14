<?php

namespace App\Http\Controllers\Auth;
use App\SocialProvider;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\vkontakte\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

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
            // sign in
            if (SocialProvider::where('id',$user_exists->id)->first()) {
                Auth::loginUsingId($user_exists->id, TRUE);
                return redirect()->route('user',$user_exists->id);
            }

            // failure
            else {
                if ($auth)
                {
                    $user_exists->verified = 1;
                    $newSignUp = SocialProvider::create([
                        'user_id' => $auth,
                        'social_id' => $userSocial->id,
                        'social_provider' => 0
                    ]);

                    return redirect()->route('user',$user_exists->id);
                }
                else
                {
                    abort(400);
                }
                return redirect()->route('ad.index');
            }
        }

        abort(500);
    }

}
