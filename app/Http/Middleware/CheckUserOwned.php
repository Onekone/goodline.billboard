<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class CheckUserOwned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route()->parameter('id');
        
        if(Auth::Check() && Auth::id()==$id) {
            return $next($request);
        }
        Session::flash('status','Это действие вам недоступно');
        Session::flash('status-class','alert-danger');
        return redirect()->back();
    }
}
