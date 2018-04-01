<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class BeforeMiddleware
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
        /*if ( $request->user()->user_role_id <= 4 )
        {
            return redirect('/home');
        }*/
        /*if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }*/
//var_dump(Auth::user()->user_role_id);dd();
        // if(Auth::user()->user_role_id > 4) {
        //     return redirect()->guest('login');
        // }

        return $next($request);
    }
}
