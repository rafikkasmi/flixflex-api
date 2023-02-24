<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @group Authentication Routes
 *
 * APIs for signing up and signing in
 */
class UserController extends Controller
{
    
    /**
     * Sign up
     *
     * This endpoint lets you create an account.
     */
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|unique:users|email",
            "username" => "required|unique:users",
            "password" => "required",
            "cn_password" => "required|same:password",
        ]);
        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);
        $user = User::create($userData);
        return response()->json($user);
    }


    /**
     * Sign in
     *
     * This endpoint lets you login , and get the auth token.
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required_without:username|email|exists:users",
            "username" => "required_without:email|exists:users",
            "password" => "required",
        ]);
        if($request->email){
            $user = User::where('email', $request->email)->first();
        }else{
            $user = User::where('username', $request->username)->first();
        }
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth_token')->plainTextToken;
                $response = ['message'=>"Login successful",'user' => $user, 'token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }
}
