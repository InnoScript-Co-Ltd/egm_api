<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\MemberCardStoreRequest;
use App\Http\Requests\MemberCardUpdateRequest;
use App\Models\File;
use App\Models\MemberCard;
use Illuminate\Support\Facades\DB;

class MemberCardController extends Controller
{
    private function uploadFile($payload, $category)
    {
        $image_path = $payload->store('images', 'public');
        $name = explode('/', $image_path)[1];

        try {
            $file = File::create([
                'name' => $name,
                'category' => 'MEMBER_CARD',
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ]);

            return $file['id'];

        } catch (Exception $e) {
            DB::rollback();

            return $e;
        }
    }

    public function index()
    {
        DB::beginTransaction();

        try {
            $memberCards = MemberCard::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('member cards list is successfully retrived', $memberCards);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MemberCardStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            if (isset($payload['front_background'])) {
                $payload['front_background'] = uploadFile($payload['front_background'], 'MEMBER_CARD_FRONT_BACKGROUND');
            }

            if (isset($payload['back_background'])) {
                $payload['back_background'] = uploadFile($payload['back_background'], 'MEMBER_CARD_BACK_BACKGROUND');
            }

            $memberCard = MemberCard::create($payload->toArray());
            DB::commit();

            return $this->success('Member card is created successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $memberCard = MemberCard::findOrFail($id);
            DB::commit();

            return $this->success('member card detail is successfully retrived', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MemberCardUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $memberCard = MemberCard::findOrFail($id);

            if (isset($payload['front_background'])) {
                $payload['front_background'] = uploadFile($payload['front_background'], 'MEMBER_CARD_FRONT_BACKGROUND');
            }

            if (isset($payload['back_background'])) {
                $payload['back_background'] = uploadFile($payload['back_background'], 'MEMBER_CARD_BACK_BACKGROUND');
            }

            $memberCard->update($payload->toArray());
            DB::commit();

            return $this->success('Member card is updated successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $memberCard = MemberCard::findOrFail($id);
            $memberCard->delete($id);
            DB::commit();

            return $this->success('Member card is deleted successfully', $memberCard);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
