<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helper\JWTToken;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result=JWTToken::VerifyToken($token);
        if($result=="unauthorized"){
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        else{
            $request->header->set('email', $result->user_email);
            $request->header->set('id', $result->user_id);
            return $next($request);

        }
    }
}
