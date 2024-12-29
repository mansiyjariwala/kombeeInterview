<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use Illuminate\Support\Facades\Session;



class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string ...$roles): Response
    {
        $userData = Session::get('user');
        
        // if($userData)
        // {
        //     return $next($request);
        // }
        // else
        // {
        //     return redirect()->route('login');
        // }

        $userData = Session::get('user');
        
        if (!$userData) {
            return redirect()->route('login');
        }

        if (isset($userData->roles)) {
            $userRoles = $userData->roles->pluck('id')->toArray();
            // dd($roles);
            if (!empty($roles) && !array_intersect($userRoles, $roles)) {
                return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    
    }
}
