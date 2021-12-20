<?php

namespace App\Http\Middleware;

use App\Models\LoginToken;
use Closure;
use Illuminate\Http\Request;

class AuthKey
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
        $token = LoginToken::where("token",$request->header("Token-Login"))->first();
        if(!$token){
            return response()->json(["message" => "not authorized"], 401);
        }

        return $next($request);
    }
}
