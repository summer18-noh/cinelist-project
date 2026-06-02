<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    private function adminOnly()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Access denied. Admins only.');
        }
    }

    public function index()
    {
        $this->adminOnly();
        $users = User::withCount('movies')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->adminOnly();
        return view('users.create');
    }

    public function store(Request $request)
    {
        $this->adminOnly();

        $request->validate([
            'name'                  => 'required|string|min:2|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'role'                  => 'required|in:admin,user',
            'bio'                   => 'nullable|string|max:300',
            'profile_image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'      => 'Full name is required.',
            'email.required'     => 'Email is required.',
            'email.unique'       => 'This email is already taken.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'role.required'      => 'Role is required.',
        ]);

        $data = $request->except(['password', 'password_confirmation', 'profile_image', '_token']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')
            ->with('toast_success', $request->name . ' has been added!');
    }

    public function show(User $user)
    {
        $this->adminOnly();
        $movies = $user->movies()->latest()->paginate(5);
        return view('users.show', compact('user', 'movies'));
    }

    public function edit(User $user)
    {
        $this->adminOnly();
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->adminOnly();

        $request->validate([
            'name'          => 'required|string|min:2|max:100',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'password'      => 'nullable|min:6|confirmed',
            'role'          => 'required|in:admin,user',
            'bio'           => 'nullable|string|max:300',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'email.unique'       => 'This email is already taken.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $data = $request->except(['password', 'password_confirmation', 'profile_image', '_token', '_method']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $request->file('profile_image')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('toast_success', $user->name . ' has been updated!');
    }

    public function destroy(User $user)
    {
        $this->adminOnly();

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('toast_error', 'You cannot delete your own account.');
        }

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('toast_error', $name . ' has been deleted.');
    }
}