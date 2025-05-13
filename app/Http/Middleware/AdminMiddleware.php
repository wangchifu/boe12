<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AdminMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::user()->group_id==9 or auth()->user()->admin==1){
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }
    }
}
