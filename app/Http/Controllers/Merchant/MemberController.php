<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Member;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {

            $members = Member::with(['users', 'membercard'])
                ->searchQuery()
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

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $member = Member::with(['users'])->findOrFail($id);
            DB::commit();

            return $this->success('member detail is successfully retrived', $member);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
