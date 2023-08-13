<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class RegisterController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role'=>'user'
        ]);
    
        $token = $user->createToken('apiToken')->accessToken;
    
        return response(['user' => $user, 'access_token' => $token]);
    }
    public function login(Request $request)
    {   
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('apiToken')->accessToken;

            return response(['user' => $user, 'access_token' => $token]);
        } else {
            return response(['error' => 'Invalid credentials'], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
        Session::flush();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
