<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;    
use App\Http\Requests\RegisterRequest;
use App\Models\State;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use App\Events\UserRegistered;
use App\Models\UserFile;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Auth;
use Illuminate\Support\Facades\Log; 

class AuthController extends Controller
{
    public function index()
    {
        $states = State::all();
        $roles = Role::whereNotIn('name', ['admin'])->get();
        return view('register', compact('states','roles'));
    }

    public function getCitiesByState($state_id)
    {
        $cities = City::where('state_id', $state_id)->get();
        return response()->json($cities);
    }

    public function register(RegisterRequest $request)
    {
        try
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

            event(new UserRegistered($user));
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            Session::put('user', $user);
            // dd("token",$token);
            return response()->json(['message' => 'Register successfully!', 'user' => $user, 'token' => $token], 200);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function loginIndex()
    {

        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        log::info(['user',$user]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::user()->createToken('API Token')->accessToken;
            Session::put('user', $user);
            Session::put('token', $token->token);
            // return response()->json(['success' => true, 'token' => $token]);
            return response()->json(['message' => 'Login successfully!', 'token' => $token->token, 'user' => $user->roles[0]->name , 'userData' => $user], 200)->header('Authorization', 'Bearer ' . $token->token);
        }

        return response()->json(['success' => false, 'message' => 'Invalid credentials.'], 401);
    }

    public function dashboard()
    {
        // $user = Auth::user();
        $userData = Session::get('user');
        $token = Session::get('token');
        // dd($userData);
        if($userData)
        {
            return view('dashboard',compact('userData','token'));
        }
        else
        {
            return redirect()->route('login');
        }
        
    }

}
