<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // get api token
        $api_token = $request->bearerToken();
        
        if(is_null($api_token)){
            return response()->json([
                'status' => 403,
                'message' => 'Invalid api key'
            ], 403);
        }
        
        // check if the token is valid
        if(!Admin::where('api_token', $api_token)->exists()){
            return response()->json([
                'status' => 403,
                'message' => 'Invalid api key'
            ], 403);
        }

        return $next($request);
    }
}
