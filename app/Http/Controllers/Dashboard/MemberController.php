<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\MemberStoreRequest;
use App\Http\Requests\MemberUpdateRequest;
use App\Models\Member;
use App\Models\MemberDiscount;
use Exception;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function nextMemberId()
    {
        DB::beginTransaction();

        try {
            $getMemberId = Member::orderBy('member_id', 'ASC')->pluck('member_id')->toArray();
            $nextMember = '000001';

            if (count($getMemberId) > 0) {
                $lastMember = $getMemberId[count($getMemberId) - 1];
                $nextMember = (int) $lastMember + 1;
                $insertZero = '';

                for ($z = 1; $z <= (6 - strlen($nextMember)); $z++) {
                    $insertZero .= '0';
                }

                $nextMember = $insertZero.$nextMember;
            }

            return $this->success('Last member card id is successfully retrived', $nextMember);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function index()
    {
        DB::beginTransaction();

        try {
            $members = Member::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('member list is successfully retrived', $members);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MemberStoreRequest $request)
    {

        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $member = Member::create($payload->toArray());
            DB::commit();

            return $this->success('Member is created successfully', $member);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $member = Member::with(['user', 'membercards'])->findOrFail($id);

            $member['membercards'] = $member['membercards']->map(function ($membercard) {
                $membercard['discounts'] = MemberDiscount::where(['id' => $membercard['discount_id']])->get();

                return $membercard;
            });

            DB::commit();

            return $this->success('member detail is successfully retrived', $member);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(MemberUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $member = Member::findOrFail($id);
            $member->update($payload->toArray());
            DB::commit();

            return $this->success('Member is updated successfully', $member);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $member = Member::findOrFail($id);
            $member->delete();
            DB::commit();

            return $this->success('Member is deleted successfully', $member);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // public function export()
    // {
    //     return Excel::download(new ExportItem, 'Items.xlsx');
    // }

    // public function import()
    // {
    //     Excel::import(new ImportItem, request()->file('file'));

    //     return $this->success('Item is imported successfully');
    // }
}
