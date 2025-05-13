<?php
namespace App\Http\Middleware;

use App\Models\UserPower;
use Closure;
use Illuminate\Support\Facades\Auth;
class AllAdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user_power = UserPower::where('user_id',auth()->user()->id)
                ->where('power_type','A')
                ->first();

            if(Auth::user()->group_id==9 or Auth::user()->group_id==8 or auth()->user()->admin==1 or (!empty(auth()->user()->section_id) and !empty($user_power))){
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }
    }
}
