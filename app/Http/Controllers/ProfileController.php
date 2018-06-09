<?php

namespace App\Http\Controllers;

use App\EmailVerify;
use Illuminate\Http\Request;
use App\Ad;
use App\User;
use Auth;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);

        $posts = Ad::where('user_id',$id)->orderBy('created_at','desc')->get();
        $auth = Auth::user()->id;

        // view
        return view('profile.profile')->withPosts($posts)->withAuth($auth)->withUser($user);
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
