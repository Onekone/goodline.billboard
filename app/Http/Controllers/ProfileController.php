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
            $auth = Auth::user()->id;

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

            if ($auth==$user->id && Hash::check($request->password, $user->password)) {
                $user->name = $request->username;
                if ($user->email != $request->useremail) {
                    $user->verified = 0;
                    $ev = EmailVerify::create([
                        'user_id' => $user->id,
                        'verify_token' => str_random(60)
                    ]);
                    Mail::to($request->useremail)->send(new EmailVerifyAccount($request->name,$ev->verify_token));
                }
                if ($request->changePassQuestion == "changePasswordQuestion"){
                    $user->password=Hash::make($request->password_new);
                }
                $user->email = $request->useremail;
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
        else {

        };


        return view('home');
    }
}
