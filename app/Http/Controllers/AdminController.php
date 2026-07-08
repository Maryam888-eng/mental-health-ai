<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()

    {
        return view('admin.dashboard', [
            'doctors' => User::where('role', 'doctor')->latest()->get(),
            'patients' => User::where('role', 'user')->latest()->get(),
        ]);
    }

    public function store(Request $request)

    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role' => ['required', Rule::in(['user', 'doctor'])],
            'password' => ['required', 'min:6'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json(['message' => 'User created successfully.']);

    }

    public function update(Request $request, User $user)

    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'doctor'])],
            'password' => ['nullable', 'min:6'],

        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully.']);

    }

    public function destroy(User $user)

    {

        if ($user->role === 'admin') {

            return response()->json(['message' => 'Admin users cannot be deleted.'], 403);

        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);

    }
}
