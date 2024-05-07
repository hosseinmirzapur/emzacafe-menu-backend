<?php

namespace App\Http\Controllers;


use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', Rule::exists('admins', 'username')],
            'password' => Password::min(8)->required()
        ]);

        $admin = Admin::query()->where('username', $data['username'])->first();
        if (!$admin) {
            return response()->json([
                'error' => trans('messages.login_failed'),
                'success' => false
            ], 400);
        }

        if (!Hash::check($data['password'], $admin->password)) {
            return response()->json([
                'error' => trans('messages.login_failed'),
                'success' => false
            ], 400);
        }

        return response()->json([
            'token' => $admin->genToken($admin->username),
            'success' => true
        ]);
    }

    public function signup(Request $request)
    {
        $data = $request->validate([
           'username' => 'required|unique:admins',
           'password' => Password::min(8)->required(),
            'branch_id' => ['required', Rule::exists('branch', 'id')]
        ]);

        Admin::query()->create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'branch_id' => $data['branch_id'],
        ]);

        return response()->json(['success' => true]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true
        ]);
    }

    public function admin()
    {
        return response()->json([
            'success' => true,
            'admin' => auth()->user()
        ]);
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'currentPassword' => ['required', Password::min(8)->required()],
            'newPassword' => ['required', Password::min(8)->required()],
            'confirmPassword' => ['required', Password::min(8)->required()],
        ]);

        if (!Hash::check($data['currentPassword'], $request->user()->password)) {
            return response()->json([
                'error' => trans('messages.wrong_password'),
            ], 400);
        }

        if ($data['newPassword'] != $data['confirmPassword']) {
            return response()->json([
                'error' => trans('messages.wrong_confirm_password'),
            ], 400);
        }

        $request->user()->update([
            'password' => Hash::make($data['newPassword'])
        ]);

        return response()->json(['success' => true]);
    }
}
