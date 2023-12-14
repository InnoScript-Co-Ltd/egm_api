<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;

class RoleController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $role = Role::searchQuery()
                ->sortingQuery()
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
            $role = Role::with(['permissions'])->findOrFail($id);

            $getPermission = $role->toArray()['permissions'];

            $permissions = collect($getPermission)->map(function ($permission) {
                return $permission['id'];
            })->toArray();

            $role->update($roleUpdatePayload);

            $spatieRole = SpatieRole::findByName($payload['name']);

            $spatieRole->revokePermissionTo($permissions);

            $spatieRole->syncPermissions($payload['permissions']);

            DB::commit();

            return $this->success('role is successfully updated', $role);
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
}
