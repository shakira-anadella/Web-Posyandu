<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // 1. Tampilkan halaman Form Login
    public function showLogin()
    {
        if (session()->has('kader_logged_in')) {
            return redirect('/dashboard');
        }
        return view('layouts.login_posyandu');
    }

    // 2. Proses Validasi Akun Kader
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Kunci Akun Khusus Posyandu Durian 14A di sini
        $username_kader = "kader14a";
        $password_kader = "durian14a"; 

        if ($request->username == $username_kader && $request->password == $password_kader) {
            // Jika benar, titipkan tiket masuk di session browser
            session(['kader_logged_in' => true]);
            return redirect('/dashboard');
        }

        // Jika salah, tendang balik dengan pesan eror
        return back()->with('error_login', 'Username atau Password Kader Salah!');
    }

    // 3. Proses Log Out
    public function logout()
    {
        session()->forget('kader_logged_in');
        return redirect('/login')->with('success_logout', 'Berhasil keluar sistem.');
    }
}