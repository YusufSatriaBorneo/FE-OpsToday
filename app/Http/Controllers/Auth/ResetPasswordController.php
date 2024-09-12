<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    // Menampilkan form reset password
    public function showResetForm($token)
    {
        return view('auth.reset_password_form', ['token' => $token]);
    }

    // Menangani permintaan reset password
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required'
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Validasi token reset password
        if (!Password::tokenExists($user, $request->token)) {
            return back()->withErrors(['email' => 'Invalid token.']);
        }

        // Update password user
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Your password has been reset!');
    }
}
