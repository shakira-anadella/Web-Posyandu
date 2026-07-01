<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (session()->has('kader_logged_in')) {
            return redirect('/dashboard');
        }
        return view('layouts.login_posyandu');
    }

    public function prosesLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username_kader = "kader14a";
        $password_kader = "durian14a"; 

        if ($request->username == $username_kader && $request->password == $password_kader) {
            session(['kader_logged_in' => true]);
            return redirect('/dashboard');
        }

        return back()->with('error_login', 'Username atau Password Kader Salah!');
    }

    public function logout()
    {
        session()->forget('kader_logged_in');
        return redirect('/login')->with('success_logout', 'Berhasil keluar sistem.');
    }
}