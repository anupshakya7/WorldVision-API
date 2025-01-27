<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            $user = Auth::user();

            //World Vision
            if($user->company_id == 1 && ($request->is('admin') || $request->is('admin/*'))){
                return $next($request);
            }elseif($user->company_id == 2 && ($request->is('admin') || $request->is('admin/*'))){
                return redirect(RouteServiceProvider::ATIHOME);
            }

            //ATI
            if($user->company_id == 2 && ($request->is('admin-ati') || $request->is('admin-ati/*'))){
                return $next($request);
            }elseif($user->company_id == 1 && ($request->is('admin-ati') || $request->is('admin-ati/*'))){
                return redirect(RouteServiceProvider::HOME);
            }
        }
        return $next($request);
    }
}
