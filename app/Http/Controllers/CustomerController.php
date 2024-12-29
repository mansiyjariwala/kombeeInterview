<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Http\Requests\CustomerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('customers.index',compact('token','userData'));
    }

    public function customerData()
    {
        // $token = $request->header('Authorization');
        // $token = str_replace('Bearer ', '', $token);    
        $token = Session::get('token');
        $user_data = Session::get('user');
        $users = User::with('roles')->whereNotIn('id', [$user_data->id, 1])->whereHas('roles', function ($query) {
            $query->where('name', 'Customer'); 
        })->select(['id', 'firstname', 'lastname', 'email', 'contact_number', 'state_id', 'city_id']);
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
