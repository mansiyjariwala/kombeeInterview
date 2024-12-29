<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('admin.roles.index',compact('token','userData'));
    }

    public function roleData()
    {
        $roles = Role::select(['id', 'name','description'])->where('name', '!=', 'admin');
        return DataTables::of($roles)
            ->addColumn('action', function($role) {
                return '<a href="'.route('role.edit', $role->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-role" data-id="'.$role->id.'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('admin.roles.create',compact('token','userData'));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated());

        return response()->json($role, 201);
    }

    public function edit($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role','token','userData'));
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());

        return response()->json($role, 200);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->users()->detach();
        foreach ($role->users as $user) {
            if ($user->roles()->count() === 0) {
                $user->delete();
            }
        }
        $role->delete();

        return response()->json(['message' => 'Role and related records deleted successfully.'], 204);
    }
}
