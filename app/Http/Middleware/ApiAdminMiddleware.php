<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check())
        {

            if($request->user()->tokenCan('server:admin'))
            {
                return $next($request);
            }
            else
            {
                return response()->json(['message'=>'Access denied! As you are not Admin',],403);
                
             }    

        }
        else{
            return response()->json([
                'status'=>401,
                'message'=>'Please Login firts'
            ]);

        }
    }
}