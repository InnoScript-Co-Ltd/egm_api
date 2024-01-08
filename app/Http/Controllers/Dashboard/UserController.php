<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PointLabelEnum;
use App\Exports\ExportUser;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Point;
use App\Models\User;
use App\Models\File;
use App\Helpers\Snowflake;
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
                "name" => $profile['name'],
                'category' => "ITEM",
                'size' => $profile['size'],
                'type' => $profile['type'],
            ]);
            
            if($uploadFile) {

                $payload['profile'] = $uploadFile->toArray()['id'];

                $point = collect(Point::where(['label' => PointLabelEnum::LOGIN_POINT->value])->first());
                $payload['reward_point'] = $point ? $point['point'] : 0;
    
                $user = User::create($payload->toArray());
                DB::commit();
    
                return $this->success('User is created successfully', $user);
            }else {
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

    public function update(UserUpdateRequest $request,)
    {

        $payload = collect($request->validated());

        DB::beginTransaction();
        try {
            /**
             * Check profile field is file format
             * **/
            if($payload->has('profile') && $payload->get('profile') instanceof \Illuminate\Http\UploadedFile){
                
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

                $uploadFile = File::create([
                    "name" => $profile['name'],
                    'category' => "ITEM",
                    'size' => $profile['size'],
                    'type' => $profile['type'],
                ]);

                /**
                 * Check file is crated
                 * **/

                if($uploadFile) {

                    $payload['profile'] = $uploadFile->toArray()['id'];
    
                    $point = collect(Point::where(['label' => PointLabelEnum::LOGIN_POINT->value])->first());
                    $payload['reward_point'] = $point ? $point['point'] : 0;
        
                    $user = User::findOrFail($payload->toArray()['id']);
                    $user->update($payload->toArray());
                    DB::commit();
        
                    return $this->success('User is updated successfully', $user);
                }else {
                    DB::commit();
    
                    return $this->validationError('Item is created fialed', [
                        'images' => ['can not upload image files'],
                    ]);
                }

            }else {

                /**
                 * profile field is already have and not changes file 
                 * **/

                $file = File::findOrFail($payload['profile']);
        
                    $point = collect(Point::where(['label' => PointLabelEnum::LOGIN_POINT->value])->first());
                    $payload['reward_point'] = $point ? $point['point'] : 0;
                    $payload['profile'] = $file->toArray()['id'];
                    $user = User::findOrFail($payload->toArray()['id']);
                    $user->update($payload->toArray());
                    DB::commit();
            
                    return $this->success('User is updated successfully', $user);
                
            }

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
