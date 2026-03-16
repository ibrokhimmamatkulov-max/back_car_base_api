<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('id')->get();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:50|unique:auto_baza.roles,name',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $role = Role::create($validator->validated());

        return response()->json([
            'message' => 'Role created successfully',
            'data'    => $role
        ], 201);
    }

    public function show($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'nullable|string|max:50|unique:auto_baza.roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $role->update($validator->validated());

        return response()->json([
            'message' => 'Role updated successfully',
            'data'    => $role
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        }

        if ($role->users()->exists()) {
            return response()->json([
                'message' => 'Role is assigned to users and cannot be deleted'
            ], 409);
        }

        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }
}
