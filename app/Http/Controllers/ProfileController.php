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
    public function show($id)
    {
        $user = User::find($id);

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

    public function update(Request $request, $id)
    {
        $this->validate($request,array(
            'username' => 'required|max:64',
            'useremail' => 'required|email|max:64',
            'password-new'=>'min:6'
        ));

        $user = User::find($id);

        if ($user) {

            $auth = Auth::id();
            $authUser = Auth::user();

            if ($auth && $auth==$user->id && (Auth::once(['email' => $user->email, 'password' => $request->password]) || $authUser->password=='')) {
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
                if ($request->changePassQuestion == "changePasswordQuestion"){
                    $user->password=Hash::make($request->password_new);
                }
                $user->save();
                return redirect()->route('user',$user->id);
            }
            return redirect()->route('home');

        }
        return redirect()->route('home');
    }



    public function nukeAds($id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            $ad_list = Ad::where('user_id',$id)->delete();
        }

        return redirect()->route('user',$id);
    }

    public function nukeUser($id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            Ad::where('user_id',$id)->delete();
            Auth::logout();
            EmailVerify::where('user_id',$id)->delete();
            SocialProvider::where('user_id',$id)->delete();
            User::where('id',$id)->delete();

        }

        return redirect()->route('home');
    }

    public function unbindVK($id) {

        $auth = Auth::user();

        if ($auth && $auth->id == $id) {
            SocialProvider::where('user_id',$id)->where('social_provider',0)->delete();
        }
        return redirect()->route('user',$id);
    }

    public function verify($key)
    {
        $tkn = EmailVerify::where('verify_token',$key)->get();

        if ($tkn){
            foreach($tkn as $user) {
                $p = User::find($user->user_id);
                $p->verified = 1;
                $p->save();
                $user->delete();
            }

        }

        return view('home');
    }
}
