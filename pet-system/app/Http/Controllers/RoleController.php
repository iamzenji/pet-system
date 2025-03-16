<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    /**
     * Display the roles management page.
     */
    public function index()
    {
        return view('pets.role');
    }

    public function fetchRoles()
    {

        $roles = Role::all();

        return DataTables::of($roles)
            ->addColumn('actions', function ($role) {
                // Return the HTML for Edit and Delete buttons
                return view('pets.role', compact('role'))->render();
            })
            ->make(true);
    }

    // ADD ROLE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Role created successfully!']);
    }

    // DELETE ROLE
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully!']);
    }
}
