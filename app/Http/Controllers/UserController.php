<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //

    public function register(Request $request){
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'userEmail' => ['required', 'string', 'email', 'max:255', new Unique('users', 'email')],
            'password' => [
                'required',
                'string',
                Password::min(8)->mixedCase()
                ->numbers()->symbols()
            ],
        ]);

            $user = User::create([
                'name' => $request->username,
                'email' => $request->userEmail,
                'password' => Hash::make($request->password),
            ]);


              
               // Return the user and token
                return response()->json([
                    'message' => 'User added',
                    'user' => $user,
                ], 201);// 201 Created status code
        
    }


    /**
     * Log in a user and return an access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request){
       // Validate the request data

       $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
       ]);

       // Attempt to authenticate the user
       if(!Auth::attempt($request->only('email', 'password'))){
            //if attempt fails
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);

        }
             // Generate a Passport token for the authenticated user
            $user = Auth::user();
            $accessToken = $user->createToken('auth-token')->accessToken;

            // Return the token
            return response()->json([
                'message' => 'Login successful',
                'expires_in' => 3600, // 1 hour
                'token' => $accessToken,
            ], 200);
         

    }

    public function logout(Request $request)
    {
        // Revoke the user's token
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
