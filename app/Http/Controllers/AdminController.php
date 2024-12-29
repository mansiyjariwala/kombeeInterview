<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userData = Session::get('user');
        return view('admin.dashboard',compact('userData'));
    }
}
