<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
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

    public function currencies(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://api.nomics.com/v1/currencies/ticker?key=0fac7334829f34a0b9879f2dc64597291aaf313a&per-page=10&page=1');
        $results = $response->getBody();
        $results = json_decode($results);
        return response()->json($results);
    }

    public function markets(Request $request)
    {
        $request->validate([
            'exchange' => 'required',
        ]);

        $client = new \GuzzleHttp\Client();
        $response = $client->get("https://api.nomics.com/v1/currencies/sparkline?key=0fac7334829f34a0b9879f2dc64597291aaf313a&ids=". $request->exchange ."&convert=EUR&start=2018-04-14T00%3A00%3A00Z&end=2018-04-25T00%3A00%3A00Z");

        $results = $response->getBody();
        $results = json_decode($results);
        return response()->json($results);
    }


}
