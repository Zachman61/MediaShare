<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;

class ApiAuth
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
        if (auth()->id())
        {
            return $next($request);
        }

        if ($request->hasHeader('X-API-KEY'))
        {
            $user = User::whereApiKey($request->header('X-API-KEY'))->first();

            if ($user)
            {
                auth()->loginUsingId($user->id);
            }
        }

        return $next($request);
    }
}
