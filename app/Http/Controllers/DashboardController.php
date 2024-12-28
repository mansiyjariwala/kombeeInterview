<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use DataTables;


class DashboardController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        
        Session::flush();
        
        return redirect()->route('login');
    }

    public function userData(Request $request)
    {
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);
        // dd($token);
        $user_data = Auth::guard('api')->user();

        // $users = User::whereNotIn('id', [$user_data->id, 1])->select(['id', 'firstname', 'lastname', 'email', 'contact_number', 'state_id', 'city_id']);
        $user = User::select('*');
        // dd($users);
        return DataTables::of($user)
            // ->addColumn('state', function($user) {
            //     return $user->state->name;
            // })
            // ->addColumn('city', function($user) {
            //     return $user->city->name;
            // })
            // // ->rawColumns(['action'])
            ->make(true);
    }

}
