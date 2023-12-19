<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;

class AdminController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $admin = Admin::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Admin list is successfully retrived', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(AdminStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $payload['password'] = bcrypt($payload['password']);
            $roleId = $payload['role_id'];
            $admin = Admin::create($payload->toArray())->assignRole($roleId);
            DB::commit();

            return $this->success('Admin is created successfully', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $admin = Admin::findOrFail($id);
            DB::commit();

            return $this->success('Admin detail is successfully retrived', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(AdminUpdateRequest $request, $id)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $admin = Admin::findOrFail($id);
            $roleId = $payload['role_id'];
            $role = SpatieRole::findOrFail($roleId);
            $currentRoleAll = $role->pluck('name')->toArray();
            $currentRole = $role->toArray()['name'];
            if ($admin->hasRole($currentRoleAll)) {
                $admin->removeRole($currentRoleAll);
            }
            $admin->update($payload->toArray())->assignRole($currentRole);
            DB::commit();

            return $this->success('Admin is updated successfully', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $admin = Admin::findOrFail($id);
            $admin->delete($id);
            DB::commit();

            return $this->success('Admin is deleted successfully', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
