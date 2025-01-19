<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return inertia('Profile/Edit',[
            'user' => $request->user(),
            'status' => session('status')
        ]);
    }

    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|max:255',
            'email' => 'required|lowercase|email|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            Rule::unique(User::class)->ignore($request->user()->id)
        ]);

        $request->user()->fill($validated);

        if($request->user()->isDirty('email'))
        {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('profile.edit')->with('status','Successfully updated info.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile.edit');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password'
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
