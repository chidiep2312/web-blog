<?php

namespace App\Http\Controllers\Web;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebController extends Controller
{
    
    public function welcome(){
        return view('welcome');
    }
    public function registerFrm(){
        return view('auth.register');
    }
    
    public function loginFrm(){
        return view('auth.login');
    }
 
    public function home(){
        return view('home');
    }

}
