<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\RemovePermissionRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Admin;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $role = Role::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Role list is successfully retrived', $role);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(RoleStoreRequest $request)
    {
        $payload = collect($request->validated());
        $payload['guard_name'] = 'api';

        DB::beginTransaction();
        try {
            $role = Role::create($payload->toArray());
            DB::commit();

            return $this->success('role is successfully created', $role);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        $roleUpdatePayload = [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'is_merchant' => $payload['is_merchant'],
        ];

        DB::beginTransaction();

        try {
            $spatieRole = SpatieRole::with(['permissions'])->findOrFail($id);

            $currentPermissions = $spatieRole->permissions->pluck('name')->toArray();

            if (isset($payload['permissions'])) {
                $spatieRole->revokePermissionTo($currentPermissions);
                $spatieRole->syncPermissions($payload['permissions']);
                $spatieRole->update($roleUpdatePayload);
            }

            DB::commit();

            return $this->success('Role is updated successfully', $spatieRole);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $role = Role::with('permissions')->findOrFail($id);

            DB::commit();

            return $this->success('role detail is successfully retrived', $role);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function assignRole(AssignRoleRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $admin = Admin::findOrFail($id);
            $admin->assignRole($payload['role']);
            DB::commit();

            return $this->success('role detail is successfully retrived', $admin);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function removePermission(RemovePermissionRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $role = SpatieRole::where(['id' => $id])->first();

            $permissions = Permission::whereIn('id', $payload['permissions'])->pluck('name')->toArray();
            $role->revokePermissionTo($permissions);
            DB::commit();

            return $this->success('Role detail is successfully retrived', $role);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
