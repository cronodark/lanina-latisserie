<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $address = $user->address()->latest()->first();

        return view('pages.profile.index', [
            'title' => 'Profil Saya',
            'user' => $user,
            'address' => $address,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'telp' => ['nullable', 'string', 'regex:/^[0-9]{9,13}$/', 'unique:users,telp,' . $user->id],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'telp.regex' => 'Nomor telepon hanya boleh berisi angka (9-13 digit).',
        ]);

        $updates = collect($validatedData)
            ->except('photo')
            ->filter(fn ($value) => filled($value))
            ->all();

        if ($request->hasFile('photo')) {
            $user->addMedia($request->file('photo'))->toMediaCollection('user-profile');
        }

        if (! empty($updates)) {
            $user->update($updates);
        }

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
