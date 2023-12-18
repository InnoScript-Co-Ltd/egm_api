<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\RemovePermissionRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
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
        ];

        DB::beginTransaction();

        try {
            $role = SpatieRole::findOrFail($id);
            $currentPermissions = $role->permissions->pluck('name')->toArray();

            if (isset($payload['permissions'])) {
                $role->revokePermissionTo($currentPermissions);
                $role->syncPermissions($payload['permissions']);
                $role->update($roleUpdatePayload);
            }

            DB::commit();

            return $this->success('Role is updated successfully', $role);
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
