<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return inertia('Admin/Users', ['users' => $users]);
    }
    public function store(Request $request)
    {
        // Conditionally apply validation rules based on the role
        $validationRules = [
            'username' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email', // Ensure unique email
            'role' => 'required|in:admin,customer', // Validate the role
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20'
        ];

        // If the role is 'admin', require the password and password confirmation
        if ($request->role === 'admin') {
            $validationRules['password'] = 'required|confirmed|min:8'; // Password validation
        }

        // Validate the request data
        $validated = $request->validate($validationRules);

        // If the role is 'admin', hash the password
        if ($request->role === 'admin' && $request->has('password') && $request->password) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            // If the role is 'customer', set a default password (or empty string)
            $validated['password'] = bcrypt('default_password'); // Default password for customer
        }

        // Create the user record
        User::create($validated);

        // Return success response with inertia redirect
        return back();
    }

    public function update(Request $request, $id)
    {
        // Validation rules for username and email
        $validationRules = [
            'username' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $id, // Ensure unique email for this user
        ];

        // Validate the request data
        $validated = $request->validate($validationRules);

        // Retrieve the user by ID
        $user = User::findOrFail($id);

        // Update only the username and email
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        // Save the user record
        $user->save();

        // Return success response with inertia redirect
        return back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back();
    }
}
