<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App\Ad;
use Illuminate\Support\Facades\Auth;

class CheckAdsMiddleware
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
        $posts = sizeof(Ad::where('user_id',Auth::id())->get());
        if($posts<5) {
            return $next($request);
        }
        Session::flash('status','Сначала вам необходимо удалить одно из обьявлений.');
        Session::flash('status-class','alert-warning');
        return redirect()->back();
    }
}
