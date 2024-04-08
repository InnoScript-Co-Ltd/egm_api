<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ClientUserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientUserController extends Controller
{
    public function update(ClientUserUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);

            if (isset($payload['profile']) && is_file($payload['profile'])) {
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

    public function show($id)
    {
        DB::beginTransaction();
        try {
            
            $user = User::with(['profile'])->findOrFail($id);
            DB::commit();

            return $this->success('User details is successfully retrived', $user);

        } catch (Exception $e) {
            throw $e;
            DB::rollback();
        }
    }
}
