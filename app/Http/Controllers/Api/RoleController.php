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
        return $this->success(Role::orderBy('id')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:50|unique:auto_baza.roles,name',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $role = Role::create($validator->validated());
        return $this->success($role, 'Role created successfully', 201);
    }

    public function show(int $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->error('Role not found', 404);
        }
        return $this->success($role);
    }

    public function update(Request $request, int $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->error('Role not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'name'        => 'nullable|string|max:50|unique:auto_baza.roles,name,' . $role->id,
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', 422, $validator->errors());
        }

        $role->update($validator->validated());
        return $this->success($role, 'Role updated successfully');
    }

    public function destroy(int $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return $this->error('Role not found', 404);
        }

        if ($role->users()->exists()) {
            return $this->error('Role is assigned to users and cannot be deleted', 409);
        }

        $role->delete();
        return $this->success(null, 'Role deleted successfully');
    }
}
