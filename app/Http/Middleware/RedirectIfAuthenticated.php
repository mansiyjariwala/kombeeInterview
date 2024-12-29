<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $userData = Session::get('user');
        if(isset($userData->roles))
        {
            $roleIds = $userData->roles->pluck('id')->toArray(); // Extract all role IDs into a new array
            if (isset($roleIds) && in_array(1, $roleIds)) {
                $role = "admin";
            }
        }

        if (isset($userData)) {
            if($role == "admin")
            {
                return redirect()->route('admin.dashboard');
            }
            else
            {
                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}
