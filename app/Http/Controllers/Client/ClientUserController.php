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

            if (isset($payload['profile'])) {
                $imagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $imagePath)[1];
                $user->profile()->updateOrCreate(['imageable_id' => $user->id], [
                    'image' => $profileImage,
                    'imageable_id' => $user->id,
                ]);
            }

            $user->update($payload->toArray());

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
