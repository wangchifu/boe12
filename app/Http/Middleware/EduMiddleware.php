<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class EduMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {

        if(Auth::guard($guard)->check()){
            if (!empty(auth()->user()->section_id or auth()->user()->group_id==2)) {
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }
    }
}
