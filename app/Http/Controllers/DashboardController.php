<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        
        Session::flush();
        
        return redirect()->route('login');
    }
}
