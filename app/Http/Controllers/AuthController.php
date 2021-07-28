<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->request->add([
            'username' => $request->email,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'scope' => '*'
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $response = Route::dispatch($proxy);
        $decondedResponse = json_decode($response->getContent());

        if(isset($decondedResponse->access_token)){
            Session::put('access_token',$decondedResponse->access_token);
        
        }

        return [
            'data' => $response->getContent(),
            'redirectUrl' => route('search')
        ];
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $accessToken = $user->createToken('User Access Token')->accessToken;

        Session::put('access_token',$accessToken);


        return response()->json([
            'data' => [
                'user' => $user,
                'access_token' => $accessToken,
                'redirectUrl' => route('search')
            ],
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }
}
