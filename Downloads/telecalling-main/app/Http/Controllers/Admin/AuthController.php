<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;

class AuthController extends Controller
{
    // Admin Web: Show login form
    public function loadLogin()
    {
        return view('auth.form-layouts.login');
    }

    // Admin Web: Handle login (AJAX JSON response)
    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $validator->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'errors' => [ 'email' => ['The provided credentials do not match our records.'] ],
            ], 401);
        }

        $request->session()->regenerate();

        // Optional: ensure only Admins can access admin area
        $user = Auth::user();
        if (isset($user->role) && strtolower((string) $user->role) !== 'admin') {
            Auth::logout();
            return response()->json([
                'status' => false,
                'errors' => [ 'email' => ['Unauthorized.'] ],
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'redirect_url' => route('dashboard'),
        ], 200);
    }

    // Admin Web: Dashboard view
    public function dashboard()
    {
        $companies = Company::count();
        $data = [
            'companies' => $companies,
        ];
        return view('admin.dashboard', $data);
    }

    // Admin Web: Profile view
    public function profile()
    {
        return view('admin.setting-pages.profile');
    }

    // Admin Web: Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('load.login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        return back()->with('status', 'Profile updated successfully');
    }

    // Admin Web: Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('load.login');
    }
}