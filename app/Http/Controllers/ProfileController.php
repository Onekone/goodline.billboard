<?php

namespace App\Http\Controllers;


use App\EmailVerify;
use App\Mail\EmailVerifyAccount;

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

            // view
            return view('profile.profile')->withPosts($posts)->withAuth($auth)->withUser($user);
        }

            return redirect()->route('home');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,array(
            'username' => 'required|max:32',
            'useremail' => 'required|email|max:32',
            'password'=>'required',
            'password-new'=>'min:6'
        ));

        $user = User::find($id);

        if ($user) {

            $auth = Auth::user()->id;

            if ($auth && $auth==$user->id && Auth::once(['email' => $user->email, 'password' => $request->password])) {
                $user->name = $request->username;
                if ($user->email != $request->useremail)
                    if ( !Validator::make(['email' => $request->useremail], ['email' => 'required|string|email|max:255|unique:users',])->fails() ) {
                        $user->email = $request->useremail;
                        $user->verified = 0;
                        $ev = EmailVerify::firstOrCreate([
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
