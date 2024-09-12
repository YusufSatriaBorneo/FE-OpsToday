<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Mail\LoginInfoMail;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials)) {
            // Update last_login_at column with current time
            // Update last_login_at column with current time
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Redirect based on user role
            if ($user->role == 'Admin') {
                return redirect()->intended('/admin');
            } elseif ($user->role == 'Engineer') {
                return redirect()->intended('/engineer/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/dashboard');
    }
    public function sendLoginInfo($userId)
    {
        $user = User::findOrFail($userId);

        // Generate reset token
        $token = Password::createToken($user);

        // Kirim email reset password
        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

        return back()->with('success', 'Link reset password telah dikirim ke email user.');
    }
}
