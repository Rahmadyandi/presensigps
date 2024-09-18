<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        $credentials = $request->only('nip', 'password');

        if (Auth::guard('dosen')->attempt($credentials)) {
            $user = Auth::guard('dosen')->user();

            if ($user->persetujuan == 'diterima') {
                return redirect('/dashboard');
            } else {
                Auth::guard('dosen')->logout();
                return redirect('/')->with('warning', 'Akun Anda belum disetujui atau ditolak.');
            }
        }

        return redirect('/')->with('warning', 'NIP atau password salah.');
    }

    public function proseslogout()
    {
        if (Auth::guard('dosen')->check()) {
            Auth::guard('dosen')->logout();
            return redirect('/');
        }
    }
}
