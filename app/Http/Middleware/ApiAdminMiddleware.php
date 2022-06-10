<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiAdminMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request , Closure $next)
    {
        if(Auth::check())
        {
            if(Auth()->user()->tokenCan('server:admin'))
            {
                return $next($request);
            }
            else
            {
                return response()->json([
                'message'=>'Access Denied.! As you are not an Admin',
                ],403);
            }
        }
        else
        {
            return response()->json([
                'status'=> 401,
                'message'=>'Please Login First',
            ]);
        }
    }
}

