<?php
namespace App\Http\Middleware;

use App\Models\UserPower;
use Closure;
use Illuminate\Support\Facades\Auth;
class SchoolAdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()){
            $user_power = check_a_user(auth()->user()->code,auth()->user()->id);

            if (Auth::user()->group_id==1 && $user_power) {
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }

    }
}
