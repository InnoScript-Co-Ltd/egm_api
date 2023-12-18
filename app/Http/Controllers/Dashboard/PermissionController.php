<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $permission = Permission::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Permission list is successfully retrived', $permission);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $permission = Permission::findOrFail($id);
            DB::commit();

            return $this->success('Permission detail is successfully retrived', $permission);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
