<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DataTables;
use App\Http\Requests\SupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\User;
use App\Models\UserFile;
use App\Models\State;
use App\Models\City;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        return view('suppliers.index',compact('token','userData'));
    }

    public function supplierData()
    {    
        $user_data = Session::get('user');
        $token = Session::get('token');
        $users = User::with('roles')->whereNotIn('id', [$user_data->id, 1])->whereHas('roles', function ($query) {
            $query->where('name', 'Supplier'); 
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
                    <a href="'.route('supplier.edit', $user->id).'" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger delete-user" data-id="' . $user->id . '">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $states = State::all();
        $roles = Role::whereIn('name', ['Supplier'])->get();
        return view('suppliers.create',compact('token','userData','states','roles'));
    }

    public function store(SupplierRequest $request)
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

    public function edit($id)
    {
        $token = Session::get('token');
        $userData = Session::get('user');
        $supplier = User::findOrFail($id);
        $states = State::all();
        $cities = City::all();
        $roles = Role::whereIn('name', ['Supplier'])->get();
        return view('suppliers.edit', compact('supplier','token','userData','roles','states','cities'));
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = user::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Supplier deleted successfully.'], 204);
    }
}
