<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportUser;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $user = User::with(['profile'])
            ->searchQuery()
            ->sortingQuery()
            ->filterQuery()
            ->filterDateQuery()
            ->paginationQuery();

        return $this->success('User list is successfully retrived', $user);
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
        $user = User::with(['members', 'profile'])->findOrFail($id);

        return $this->success('User detail is successfully retrived', $user);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            if (isset($payload['profile'])) {
                $imagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $imagePath)[1];
                $user->profile()->updateOrCreate(['imageable_id' => $user->id], [
                    'image' => $profileImage,
                    'imageable_id' => $user->id,
                ]);
            }

            $user->update($payload->toArray());
            DB::commit();

            return $this->success('User is updated successfully', $user);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function destroy($id)
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

    public function export()
    {
        return Excel::download(new ExportUser, 'users.xlsx');
    }
}
