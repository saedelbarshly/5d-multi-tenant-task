<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user() ?? User::where('email',$request->get('email'))->first();
        $subdomain = explode('.', $request->getHost())[0];
        if($user->tenant->subdomain == $subdomain){
            return $next($request);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'You do not belong to this subdomain !'
            ], 403);
        }
    }
}
