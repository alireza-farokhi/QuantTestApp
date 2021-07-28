<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        if(request()->session()->has('access_token')){
            return redirect('search');
        }
        return view('welcome');

    }

    public function search(Request $request)
    {
        
        if(!request()->session()->has('access_token')){
            return redirect('/');
        }
        return view('search');
    }
}
