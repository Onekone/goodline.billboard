<?php

namespace App\Http\Middleware;

use Session;
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
        $userId = $post->user->id;
        if(Auth::Check() && Auth::id()===$userId) {
            return $next($request);
        }
        Session::flash('status','Это действие вам недоступно');
        Session::flash('status-class','alert-warning');
        return redirect()->route('ad.index');
    }
}
