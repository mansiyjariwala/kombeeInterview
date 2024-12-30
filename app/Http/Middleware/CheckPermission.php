<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Auth;
use App\Models\User;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        $user = Session::get('user');
        $permissions = $user->permissions();
        $loggedinUserRole = $user->roles()->get();

        // dd($loggedinUserRole);
        
        if (!($loggedinUserRole->contains('name', 'Admin') || $permissions->contains('name', $permission) )) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
