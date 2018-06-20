<?php

namespace App\Http\Middleware;

use Session;
use App\Ad;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckValidated
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

        if(Auth::Check() && Auth::user()->verified) {
            return $next($request);
        }
        Session::flash('status','Сначала вам необходимо подтвердить адрес электронной почты.');
        Session::flash('status-class','alert-warning');
        return redirect()->back();
    }
}