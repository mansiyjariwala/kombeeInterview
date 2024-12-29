<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('admin.permissions.index',compact('token','userData'));
    }

    public function permissionData()
    {
        $permission = Permission::select(['id', 'name','description']);
        return DataTables::of($permission)
            ->addColumn('action', function($permission) {
                return '<a href="'.route('permission.edit', $permission->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-permission" data-id="'.$permission->id.'">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('admin.permissions.create',compact('token','userData'));
    }

    public function store(PermissionRequest $request)
    {
        $permission = Permission::create($request->validated());
        return response()->json($permission, 201);
    }

    public function edit($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.edit', compact('permission','token','userData'));
    }

    public function update(PermissionRequest $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->update($request->validated());

        return response()->json($permission, 200);
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(null, 204);
    }
}
