<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Session;

class RolePermissionController extends Controller
{
    public function indexRole()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $roles = Role::where('name', '!=', 'admin')->get();
        $permissions = Permission::all();
        return view('role_permissions.index', compact('roles', 'permissions','userData','token'));
    }

    public function indexPermissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function store(Request $request)
    {
        foreach ($request->roles as $roleId => $permissions) {
            $role = Role::find($roleId);
            if ($role) {
                $role->permissions()->sync($permissions);
            }
        }
        
        return response()->json(['message' => 'Roles and permissions updated successfully'], 200);
    }

    public function removePermissionFromRole(Role $role, Permission $permission)
    {
        $role->permissions()->detach($permission->id);
        return response()->json(['message' => 'Permission removed successfully']);
    }
}
