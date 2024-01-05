<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;
use App\Models\File;
use App\Helpers\Snowflake;
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

            if($uploadFile){
                
                $payload['profile'] = $uploadFile->toArray()['id'];
                $payload['password'] = bcrypt($payload['password']);
                $roleId = $payload['role_id'];
                $roleName = SpatieRole::findOrFail($roleId[0]);
                $admin = Admin::create($payload->toArray())->assignRole($roleName->toArray()['name']);
                DB::commit();

            }

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

    public function update(AdminUpdateRequest $request)
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
    
                    $admin = Admin::findOrFail($payload->toArray()['id']);

                    if ($payload['role_id'] !== null) {
                        $roleId = $payload['role_id'];
                        $role = collect(SpatieRole::findOrFail($roleId))->toArray();
                        $admin->removeRole($role['name']);
                        $admin->syncRoles($role['name']);
                    }
                    $admin->update($payload->toArray());

                    DB::commit();

                    return $this->success('Admin is updated successfully', $admin);
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
        
                $admin = Admin::findOrFail($payload->toArray()['id']);

                if ($payload['role_id'] !== null) {
                    $roleId = $payload['role_id'];
                    $role = collect(SpatieRole::findOrFail($roleId))->toArray();
                    $admin->removeRole($role['name']);
                    $admin->syncRoles($role['name']);
                }
                $admin->update($payload->toArray());
    
                DB::commit();
    
                return $this->success('Admin is updated successfully', $admin);
                
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
