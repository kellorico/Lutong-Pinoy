<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return inertia('Auth/Register');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|max:255',
            'phone' => 'nullable|numeric|max:20'
        ]);

        // Create the user record
        $user = User::create($validated);

        // Log in the user
        Auth::login($user);

        // Redirect to the intended page or dashboard
        return redirect()->route('dashboard');
    }

}
