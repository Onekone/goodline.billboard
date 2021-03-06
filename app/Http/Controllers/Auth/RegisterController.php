<?php

namespace App\Http\Controllers\Auth;

use Session;
use App\EmailVerify;
use App\Mail\EmailVerifyAccount;
use App\SocialProvider;
use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/ad';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'vk_id' => 'string|unique:social_providers',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Invalid captcha code.'
        ]);
    }




    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function passVKData(Request $request)
    {
        $input = $request->all();

        return view('auth.register')->withSocialOptions([
            'social_id'=>$input['social_id'],
            'email'=>$input['email'],
            'name'=>$input['name'],
            ]);
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }

    protected function create(array $data)
    {
        $message = 'Регистрация прошла успешно, проверьте почту и подтвердите email';

        if ($data['social_id'])
        {
            $p = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'verified' => 1,
            ]);

            $sp = SocialProvider::create([
                'user_id' => $p->id,
                'social_id' =>  $data['social_id'],
                'social_provider' => 0
                ]
            );
            $message = "Успешно зарегистрировались используя аккаунт ВКонтакте";
        }
        else
        {
            $p = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'verified' => 0,
            ]);

            $ev = EmailVerify::create([
                'user_id' => $p->id,
                'verify_token' => str_random(60)
            ]);
        }

        RegisterController::flashMessage('alert-info',$message);
        Mail::to($data['email'])->send(new EmailVerifyAccount($data['name'],$ev->verify_token));

        return $p;
    }

    public function flashMessage($class,$message)
    {
        Session::flash('status-class', $class);
        Session::flash('status', $message);
    }
}
