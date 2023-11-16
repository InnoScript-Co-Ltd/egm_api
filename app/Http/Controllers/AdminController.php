<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admin = Admin::searchQuery()
            ->sortingQuery()
            ->paginationQuery();
        DB::beginTransaction();
        try {

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

            $admin = Admin::create($payload->toArray());
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
            $admin->update($payload->toArray());
            DB::commit();

            return $this->success('Admin is updated successfully', $admin);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function delete($id)
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
