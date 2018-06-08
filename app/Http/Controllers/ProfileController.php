<?php

namespace App\Http\Controllers;

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

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
