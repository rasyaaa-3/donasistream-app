<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'             => 'required|string|max:100',
            'username'         => 'required|string|max:50|unique:users,username,' . $user->id,
            'bio'              => 'nullable|string|max:500',
            'instagram'        => 'nullable|string|max:100',
            'new_password'     => ['nullable', 'min:6', 'same:confirm_password'],
            'confirm_password' => 'nullable',
        ], [
            'nama.required'         => 'Nama wajib diisi.',
            'username.required'     => 'Username wajib diisi.',
            'username.unique'       => 'Username sudah digunakan.',
            'new_password.min'      => 'Password baru minimal 6 karakter.',
            'new_password.same'     => 'Konfirmasi password tidak cocok.',
        ]);

        // Generate initials from nama
        $namaParts = explode(' ', trim($request->nama));
        $initials  = strtoupper(
            count($namaParts) >= 2
                ? substr($namaParts[0], 0, 1) . substr($namaParts[1], 0, 1)
                : substr($namaParts[0], 0, 2)
        );

        $user->nama      = $request->nama;
        $user->username  = $request->username;
        $user->bio       = $request->bio;
        $user->instagram = $request->instagram;
        $user->initials  = $initials;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
