<?php

namespace App\Http\Controllers;

use App\SocialProvider;
use App\User;
use App\EmailVerify;
use App\Mail\EmailVerifyAccount;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\vkontakte\Provider;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SocialProviderController extends Controller
{

    public function flashMessage(Request $request,$class,$message)
    {
        $request->session()->flash('status', $message);
        $request->session()->flash('status-class',$class);
    }
    //
    public function redirectToProvider()
    {

        return Socialite::with('vkontakte')->stateless()->redirect();
    }

    //
    public function handleProviderCallback(Request $request)
    {
        $userSocial = Socialite::driver('vkontakte')->stateless()->user();
        $accessTokenResponseBody = $userSocial->accessTokenResponseBody;

        // Martin Bean - Stack Overflow
        // https://stackoverflow.com/questions/29127330/laravel-5-socialite-change-auth-redirect-path-dynamically

        try {
            // Try and find user by their social profile UID
            $appUser = SocialProvider::where('social_id',$userSocial->id)->firstOrFail();
            SocialProviderController::flashMessage($request,'alert-info','Вход используя связь с ВКонтакте');
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
                SocialProviderController::flashMessage($request,'alert-info','Успешно связано с ВК');
                return redirect()->route('user',Auth::id());

            } else {
                // User is not logged in, and account does not exist
                // Prompt to register
                if ($userSocial->accessTokenResponseBody['email']) {

                    try {
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
                        SocialProviderController::flashMessage($request,'alert-info','Успешно создан аккаунт, используя данные ВКонтакте');
                        return redirect()->route('user',$newUser->id);
                    }
                    catch (\Illuminate\Database\QueryException $e)
                    {
                        SocialProviderController::flashMessage($request,'alert-danger','Пользователь с такой почтой уже есть. Возможно лучше будет войти в аккаунт и привязать его к этому аккаунту ВКонтакте');
                        return redirect()->route('register.vk', [
                            'social_id' => $userSocial->id,
                            'email' => $userSocial->accessTokenResponseBody['email'],
                            'name' => $userSocial->name,
                        ]);
                    }
                }
                else {
                    SocialProviderController::flashMessage($request,'alert-dark','На этой учетной записи ВКонтакте не указан адрес электронной почты. Для продолжения, вам надо указать его');
                    return redirect()->route('register.vk', [
                        'social_id' => $userSocial->id,
                        'email' => '',
                        'name' => $userSocial->name,
                    ],302)->withErrors(['email'=>'На этой учетной записи ВКонтакте не указан адрес электронной почты. Для продолжения вам необходимо указать его']);
                }


            }
        }
        abort(500);
    }
}
