<?php

namespace App\Http\Controllers\Auth;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;


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

        //dd(\Illuminate\Support\Facades\Request::input('first_name'));
        $first_name = Request::input('first_name');
        $uid = Request::input('uid');
        //dd($first_name);
        //$user = Socialite::driver('vkontakte')->user();
        //$accessTokenResponseBody = $user->accessTokenResponseBody;


        $newUser = User::create([
            'name' => $first_name,
            'email' => '',
        ]);

        Auth::loginUsingId($newUser->id, TRUE);

        return redirect()->route('ad.index');
    }

}
