<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PointLabelEnum;
use App\Exports\ExportUser;
use App\Helpers\Snowflake;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\File;
use App\Models\Point;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $user = User::searchQuery()
            ->sortingQuery()
            ->filterQuery()
            ->filterDateQuery()
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

        $files = $payload['profile'];

        $image_path = $files->store('images', 'public');
        $name = explode('/', $image_path)[1];
        $snowflake = new SnowFlake;

        $profile = [
            'id' => $snowflake->id(),
            'name' => $name,
            'category' => 'ITEM',
            'size' => $files->getSize(),
            'type' => $files->getMimeType(),
        ];

        DB::beginTransaction();

        try {

            $uploadFile = File::create([
                'name' => $profile['name'],
                'category' => 'ITEM',
                'size' => $profile['size'],
                'type' => $profile['type'],
            ]);

            if ($uploadFile) {

                $payload['profile'] = $uploadFile->toArray()['id'];

                $point = collect(Point::where(['label' => PointLabelEnum::LOGIN_POINT->value])->first());
                $payload['reward_point'] = $point ? $point['point'] : 0;

                $user = User::create($payload->toArray());
                DB::commit();

                return $this->success('User is created successfully', $user);
            } else {
                DB::commit();

                return $this->validationError('Item is created fialed', [
                    'images' => ['can not upload image files'],
                ]);
            }

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

            if (isset($payload['profile'])) {

                if ($user->profile) {
                    $getFile = File::findOrFail($user->profile);

                    if ($getFile) {
                        $getFile->delete($user->profile);
                    //  unlink(public_path() . "/". "storage/images/" . $getFile->name);
                    } else {
                        return $this->validationError('File is created failed', [
                            'images' => ['can not find current image'],
                        ]);
                    }
                }

                $files = $payload['profile'];
                $image_path = $files->store('images', 'public');
                $name = explode('/', $image_path)[1];

                $profilePayload = [
                    'name' => $name,
                    'category' => 'USER',
                    'size' => $files->getSize(),
                    'type' => $files->getMimeType(),
                ];

                $uploadFile = File::create($profilePayload);

                if (! $uploadFile) {
                    return $this->validationError('File is created failed', [
                        'images' => ['can not upload image files'],
                    ]);
                }

                $payload['profile'] = $uploadFile->id;
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
