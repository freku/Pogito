<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Ban;

class BanCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $is_banned = Ban::where('user_id', Auth::user()->id)->first();
            if ($is_banned) {
                Auth::logout();
                return redirect('/');
            }
        }

        return $next($request);
    }
}
