<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index ()
    {
        $user = User::searchQuery()
                    ->sortingQuery()
                    ->paginationQuery();
        DB::beginTransaction();
        try {
            
            DB::commit();
            return $this->success('User list is successfully retrived', $user);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

    public function store(UserStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {
            
            $user = User::create($payload->toArray());
            DB::commit();

            return $this->success('User is created successfully', $user);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }

    }

    public function show($id)
    {

        DB::beginTransaction();
        try {
            
            $user = User::findOrFail($id);
            DB::commit();

            return $this->success('User detail is successfully retrived', $user);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }

    }

    public function update(UserUpdateRequest $request, $id)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();
        try {
            
            $user = User::findOrFail($id);
            $user->update($payload->toArray());
            DB::commit();

            return $this->success('User is updated successfully', $user);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }

    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            
            $user = User::findOrFail($id);
            $user->delete($id);
            DB::commit();

            return $this->success('User is deleted successfully', $user);

        } catch (Exception $e) {
        DB::rollback();
        throw $e;
        }
    }

}
