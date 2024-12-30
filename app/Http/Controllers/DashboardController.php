<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Role;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\UserFile;
use App\Http\Requests\UpdateSupplierRequest;

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
        $sessionData = Session::all();
        $token = $request->header('Authorization');
        $token = str_replace('Bearer ', '', $token);    
        // dd($token);
        $user_data = Session::get('user');

        $users = User::with('roles')->whereNotIn('id', [$user_data->id, 1])->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Admin'); 
        })->select(['id', 'firstname', 'lastname', 'email', 'contact_number', 'state_id', 'city_id']);
        // dd($users->get());
        return DataTables::of($users)
            ->addColumn('state', function($user) {
                return $user->state->name ?? '-' ;
            })
            ->addColumn('city', function($user) {
                return $user->city->name ?? '-' ;
            })
            ->addColumn('action', function($user) {
                $editButton = '';
                $deleteButton = '';
            
                if ($user->roles->pluck('permissions')->flatten()->contains('name', 'update')) {
                    $editButton = '<a href="'.route('user.edit', $user->id).'" class="btn btn-sm btn-primary">Edit</a>';
                }
            
                if ($user->roles->pluck('permissions')->flatten()->contains('name', 'delete')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">Delete</button>';
                }
            
                return $editButton . $deleteButton;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $states = State::all();
        $roles = Role::whereNot('name','Admin')->get();
        // $roles = Role::with('permissions')->whereNotIn('name', ['Admin'])->get();
        $roles = $roles->filter(function ($role) {
            // Flatten all permissions associated with this role
            $permissions = $role->permissions->pluck('name')->flatten();
            
            return $permissions->contains('create');
        });
        if ($roles->isEmpty()) 
        {
            return response()->json(['message' => 'No user role has permission to be created!'], 200); 
        }
        return view('user.create',compact('token','userData','states','roles'));
    }

    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'contact_number' => $validated['contact_number'],
            'postcode' => $validated['postcode'],
            'state_id' => $validated['state'],
            'city_id' => $validated['city'],
            'password' => Hash::make($validated['password']),
            'gender' => $validated['gender'],
            'hobbies' => json_encode($validated['hobbies']),
        ]);

        if (isset($validated['roles'])) {
            $user->roles()->attach($validated['roles']);
        }

        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path() . '/files/', $name);
    
                UserFile::create([
                    'user_id' => $user->id,
                    'file_name' => $name,
                ]);
            }
        }

        return response()->json(['message' => 'Supplier Created successfully!'], 200);
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $permissions = $user->roles->pluck('permissions')->flatten();
        if ($permissions->contains('name', 'delete'))
        {
            $user->delete();
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }

        return response()->json(['message' => 'User deleted successfully.'], 204);
    }

    public function edit($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $user = User::findOrFail($id);
        $states = State::all();
        $cities = City::all();

        $user = User::with('roles')->findOrFail($id);
        $permissions = $user->roles->pluck('permissions')->flatten();
        $roles = Role::whereNotIn('name', ['Admin'])->get();
        if ($permissions->contains('name', 'update'))
        {
            return view('user.edit', compact('user','token','userData','roles','states','cities'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }

    }

}
