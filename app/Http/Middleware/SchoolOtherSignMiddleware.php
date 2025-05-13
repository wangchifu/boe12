<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class SchoolOtherSignMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::guard($guard)->check()){
            if (Auth::user()->other_code) {
                return $next($request);
            }else{
                return redirect('/');
            }
        }else{
            return redirect('glogin');
        }

    }
}
