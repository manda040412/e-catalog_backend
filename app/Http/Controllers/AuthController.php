<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Approval;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|in:super_admin,admin,internal,eksternal',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::where('role_name', $request->role)->first();
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        // Customer (internal/eksternal) harus isi data diri
        if (in_array($request->role, ['internal', 'eksternal'])) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'agency' => 'required',
                'phone_number' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Validasi email internal/eksternal
            if ($request->role == 'internal' && !str_ends_with($request->email, '@tranugerah.com')) {
                return response()->json(['error' => 'Email internal harus menggunakan @tranugerah.com'], 422);
            }
            if ($request->role == 'eksternal' && str_ends_with($request->email, '@tranugerah.com')) {
                return response()->json(['error' => 'Email eksternal tidak boleh menggunakan @tranugerah.com'], 422);
            }
        }

        $user = User::create([
            'role_id' => $role->id,
            'name' => $request->name ?? null,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'agency' => $request->agency ?? null,
            'phone_number' => $request->phone_number ?? null,
            'is_approved' => in_array($request->role, ['internal', 'eksternal']) ? 0 : 1,
        ]);

        // Insert approval jika customer
        if (in_array($request->role, ['internal', 'eksternal'])) {
            Approval::create([
                'id_user' => $user->id_user,
                'status' => 'pending',
            ]);
        }

        return response()->json(['message' => 'Register success, waiting for approval if customer', 'user' => $user]);
    }

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Username or password incorrect'], 401);
        }

        // Jika customer, cek approval
        $role = $user->role->role_name;
        if (in_array($role, ['internal', 'eksternal']) && !$user->is_approved) {
            return response()->json(['error' => 'Account not approved yet'], 403);
        }

        // Generate token (jika pakai sanctum/jwt)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token' => $token,
            'user' => $user,
            'role' => $role
        ]);
    }

    // Approve user
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = 1;
        $user->save();

        $approval = Approval::where('id_user', $id)->first();
        if ($approval) {
            $approval->status = 'approved';
            $approval->approved_by = auth()->user()->id_user;
            $approval->approved_at = now();
            $approval->save();
        }

        return response()->json(['message' => 'User approved']);
    }
}
