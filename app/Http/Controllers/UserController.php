<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //

    public function register(Request $request)
    {
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
        ], 201); // 201 Created status code

    }


    /**
     * Log in a user and return an access token.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        // Validate the request data
        //  dd($request);
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($request->only('email', 'password'))) {
            //if attempt fails
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }

        $client = DB::table('oauth_clients')->where('id', 2)->first();


        $http = new Client();
        // $response = Http::timeout(60)->asForm()->post('http://127.0.0.1:8000/oauth/token', [
        //     'grant_type' => 'password',
        //     'client_id' => $client->id,
        //     'client_secret' => $client->secret,
        //     'username' => $request->email,
        //     'password' => $request->password,
        //     'scope' => '',
        // ]);

        //  dd($http);

        $response = $http->post('http://localhost/free-fly/freefly/public/oauth/token', [
            'json' => [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ]
        ]);

       // dd($response);

        return json_decode($response->getBody(), true);
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
