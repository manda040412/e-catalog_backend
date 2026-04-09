<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ======================
    // REGISTER CUSTOMER
    // ======================
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'agency' => 'required',
                'phone_number' => 'required',
            ]);

            // 🔥 DETEKSI ROLE DARI EMAIL
            if (Str::endsWith($validated['email'], '@tranugerah.com')) {
                $role_id = 3; // internal
            } else {
                $role_id = 4; // eksternal
            }

            $user = User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'agency' => $validated['agency'],
                'phone_number' => $validated['phone_number'],
                'role_id' => $role_id,
                'is_approved' => 0 // 🔥 butuh approval admin
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Register berhasil, menunggu approval admin',
                'data' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // 🔍 GET USER PENDING
    // =========================
    public function pendingUsers()
    {
        $users = User::where('is_approved', false)->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    // =========================
    // ✅ APPROVE USER
    // =========================
    public function approveUser($id)
    {
        $user = User::findOrFail($id);

        $user->is_approved = true;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil di-approve'
        ]);
    }

    // =========================
    // ❌ REJECT USER
    // =========================
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User ditolak & dihapus'
        ]);
    }

    // ======================
    // LOGIN (ALL USER)
    // ======================
    public function login(Request $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login gagal'
            ], 401);
        }

        $user = Auth::user();

        // 🔥 CEK APPROVAL
        if (in_array($user->role_id, [3, 4]) && !$user->is_approved) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun belum di-approve admin'
            ], 403);
        }

        // 🔥 INI YANG KURANG
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => [
                'user' => $user,
                'role' => $user->role->role_name,
                'token' => $token
            ]
        ]);
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }
}