<?php

namespace App\Http\Controllers;


use App\EmailVerify;
use App\Mail\EmailVerifyAccount;

use App\SocialProvider;
use Illuminate\Http\Request;
use App\Ad;
use App\User;
use Auth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{


    public function show(Request $request, $id)
    {
        $user = User::find($id);
        $request->session()->forget('session');
        $request->session()->forget('session-class');
        if($user){
            $posts = Ad::where('user_id',$id)->orderBy('created_at','desc')->get();
            $auth = Auth::id();
            $authFull = Auth::user();
            $connectedTo = SocialProvider::where('user_id',$user->id)->where('social_provider',0)->first();
            // view
            return view('profile.profile')->withPosts($posts)->withAuth($auth)->withUser($user)->withvkLink($connectedTo);
        }

            return redirect()->route('home');
    }

    public function sendAnotherVerify(Request $request, $id)
    {
        $user = User::find($id);

        $ev = EmailVerify::firstOrNew([
            'user_id' => $user->id,
        ]);
        $ev->verify_token = str_random(60);
        $ev->save();
        Mail::to($user->email)->send(new EmailVerifyAccount($user->name,$ev->verify_token));

        ProfileController::flashMessage($request,'alert-info','Отправлено письмо с подтверждением');
        return redirect()->route('user',$id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,array(
            'username' => 'required|max:64',
            'useremail' => 'required|email|max:64|unique:users,email,'.$id,
            'password-new'=>'password|min:6'
        ));

        $user = User::find($id);


        if ($user) {

            $auth = Auth::id();
            $authUser = Auth::user();
            $managedToLogin = Auth::once(['email' => $user->email, 'password' => $request->password]);
            $message = 'Изменения успешно сохранены';
            $messageClass = 'alert-success';
            if ($auth && $auth==$user->id && ($managedToLogin || $authUser->password=='')) {
                $user->name = $request->username;
                if ($user->email != $request->useremail)
                    if ( !Validator::make(['email' => $request->useremail], ['email' => 'required|string|email|max:255|unique:users',])->fails() ) {
                        $user->email = $request->useremail;
                        $user->verified = 0;
                        $ev = EmailVerify::firstOrNew([
                            'user_id' => $user->id,
                        ]);
                        $ev->verify_token = str_random(60);
                        $ev->save();
                        Mail::to($request->useremail)->send(new EmailVerifyAccount($request->name,$ev->verify_token));
                    }
                    else {
                        $messageClass = 'alert-warning';
                        $message = 'Этот адрес электронной почты неверен или уже занят';
                    }
                if ($request->changePassQuestion == "changePasswordQuestion"){
                    $user->password=Hash::make($request->password_new);
                }
                $user->save();
                ProfileController::flashMessage($request,$messageClass,$message);
                return redirect()->route('user',$user->id);
            }
            else {
                if (!$managedToLogin)
                    ProfileController::flashMessage($request,'alert-danger','Неправильный пароль');

                return redirect()->route('user',$user->id);
            }
        }
        ProfileController::flashMessage($request,'alert-danger','Юзера, которого вы пытаетесь изменить, не существует');
        return redirect()->route('home');
    }

    public function flashMessage(Request $request,$class,$message)
    {
        $request->session()->flash('status', $message);
        $request->session()->flash('status-class',$class);
    }
    public function putMessage(Request $request,$class,$message)
    {
        $request->session()->put('status', $message);
        $request->session()->put('status-class',$class);
    }
    public function nukeAds(Request $request, $id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            $ad_list = Ad::where('user_id',$id)->delete();
        }
        ProfileController::flashMessage($request,'alert-dark','Объявления успешно удалены');
        return redirect()->route('user',$id);
    }

    public function nukeUser(Request $request, $id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            Ad::where('user_id',$id)->delete();
            ProfileController::flashMessage($request,'alert-dark','Прощайте');
            Auth::logout();
            EmailVerify::where('user_id',$id)->delete();
            SocialProvider::where('user_id',$id)->delete();
            User::where('id',$id)->delete();

        }

        return redirect()->route('ad.index');
    }

    public function unbindVK(Request $request,$id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            SocialProvider::where('user_id',$id)->where('social_provider',0)->delete();
        }
        ProfileController::flashMessage($request,'alert-info','Аккаунт ВК успешно отвязан');
        return redirect()->route('user',$id);
    }

    public function verify(Request $request, $key)
    {

        try {
            $tkn = EmailVerify::where('verify_token',$key)->firstOrFail();
            $p = User::find($tkn->user_id);

            $p->verified = 1;
            $p->save();

            $tkn->delete();

            ProfileController::flashMessage($request,'alert-success','Email успешно подтвержден');
        }
        catch (\Exception $E) {
            ProfileController::flashMessage($request,'alert-danger','Ошибка подтверждения email');
            return redirect()->back();
        }

        return view('home');
    }
}
