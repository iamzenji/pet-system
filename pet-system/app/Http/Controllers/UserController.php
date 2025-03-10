<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;

class UserController extends Controller
{
    public function account()
    {
        $roles = Role::all();
        return view('pets.account', compact('roles'));
    }

    // USER DATA
    public function getUsersData()
    {
        $users = User::with('roles')->select('id', 'name', 'email', 'created_at');

        return FacadesDataTables::of($users)
            ->addColumn('role', function ($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->make(true);
    }

    // UPDATE USER
    public function updateUser(Request $request, $id)
    {
        try {
            Log::info('Update Request Data: ', $request->all());

            $user = User::findOrFail($id);

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($request->role) {
                $role = Role::find($request->role);
                if (!$role) {
                    Log::error('Role not found: ' . $request->role);
                    return response()->json(['error' => 'Role not found'], 400);
                }

                $user->roles()->sync([
                    $role->id => ['user_type' => 'App\Models\User']
                ]);

                Log::info('Role updated to: ' . $role->name);
            }

            return response()->json(['success' => 'User updated successfully']);
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }

    // DELETE USER
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => 'User deleted successfully']);
    }

    // FETCH ROLE
    public function getRoles()
    {
        return response()->json(['roles' => Role::all()]);
    }

    // CREATE DATA
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Attach role
        $user->attachRole($request->role);

        return response()->json(['success' => 'User registered successfully!']);
    }


}
