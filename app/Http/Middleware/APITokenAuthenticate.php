<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APITokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$type)
    {
        $token = $request->bearerToken();
        $type = $type == 'worldvision' ? 1 : 2;
        
        if(!$token){
            return response()->json([
                'success'=>false,
                'message'=>'Token not provided'
            ]);
        }

        $user = User::where('api_token',$token)->first();
        
        if(!$user){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid Token'
            ]);
        }

        if($type != $user->company_id){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid Token'
            ]);
        }

        Auth::login($user);

        return $next($request);
    }
}
