<?php
namespace App\Http\Middleware;

use App\Models\UserPower;
use Closure;
use Illuminate\Support\Facades\Auth;
class SchoolSignMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if(auth()->check()){
            $user_power = check_b_user(auth()->user()->code,auth()->user()->id);

            if (Auth::guard($guard)->check() && Auth::user()->group_id==1 && $user_power) {
		return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect()->route('glogin');
        }

    }
}
