<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $movies = $user->movies()->latest()->paginate(6);
        return view('profile.index', compact('user', 'movies'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|min:2|max:100',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'bio'             => 'nullable|string|max:300',
            'current_password'=> 'nullable|string',
            'new_password'    => 'nullable|min:6|confirmed',
            'profile_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'         => 'Full name is required.',
            'email.unique'          => 'This email is already taken.',
            'new_password.min'      => 'New password must be at least 6 characters.',
            'new_password.confirmed'=> 'New passwords do not match.',
        ]);

        // --- UPDATE BASIC INFO ---
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->bio   = $request->bio;

        // --- UPDATE PASSWORD ---
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Current password is incorrect.',
                ])->with('toast_error', 'Current password is incorrect.');
            }
            $user->password = Hash::make($request->new_password);
        }

        // --- UPDATE PROFILE IMAGE ---
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')
                                           ->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('profile.index')
            ->with('toast_success', 'Profile updated successfully!');
    }
}