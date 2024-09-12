<?php

namespace App\Http\Controllers;

use App\Mail\LoginInfoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    // Fungsi untuk menyimpan user baru
    public function store(Request $request)
    {
        $password = Str::random(8); // Generate a random password
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
            'role' => $request->role,
            'engineer_id' => $request->engineer_id,
        ]);

        // Send email with login info
        Mail::to($user->email)
            ->cc(['Bayu.Adhitya@kpc.co.id']) // Tambahkan CC jika diperlukan
            ->send(new LoginInfoMail($user, $password));

        return redirect()->route('admin.users')->with('success', 'User created and login info sent to email.');
    }
}