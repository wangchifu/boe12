<?php
namespace App\Http\Middleware;

use App\Models\UserPower;
use Closure;
use Illuminate\Support\Facades\Auth;
class EduAdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()){
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();

            if (!empty($user_power)) {
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }

    }
}
