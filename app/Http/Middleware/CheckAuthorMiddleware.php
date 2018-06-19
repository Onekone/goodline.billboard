<?php

namespace App\Http\Middleware;

use App\Ad;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route()->parameter('ad');
        $post = Ad::find($id);
        $username = User::where('id', $post->user_id)->get()[0]->name;
        if(Auth::Check() && Auth::user()->name==$username) {
            return $next($request);
        }
        return redirect()->route('ad.index');
    }
}
