<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    /**
     * Show Login page
     *
     */
    public function login(){
        return view('dashboard-admin.auth.login');
    }
    /**
     * authentication and go to dashboard
     */
    public function authenticate(Request $request){
        $certification = $request->only('email','password');
  //      dd($certification);
        if(Auth::attempt($certification)){
            return redirect('admin/dashboard');
        }
        return redirect('admin/login');
    }
}
