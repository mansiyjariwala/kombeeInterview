<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Models\User;


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
        $user_data = Session::get('user');

        $users = User::whereNotIn('id', [$user_data->id, 1])->select(['id', 'firstname', 'lastname', 'email', 'contact_number', 'state_id', 'city_id']);
        // dd($users->get());
        return DataTables::of($users)
            ->addColumn('state', function($user) {
                return $user->state->name ?? '-' ;
            })
            ->addColumn('city', function($user) {
                return $user->city->name ?? '-' ;
            })
            ->addColumn('action', function($user) {
                return '
                    <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

}
