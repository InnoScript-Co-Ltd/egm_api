<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\MembershipOrderStoreReqeust;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberDiscount;
use App\Models\MemberOrder;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class MembershipOrderController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $orders = MemberOrder::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('order list is successfully retrived', $orders);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $order = MemberOrder::findOrFail($id);
            DB::commit();

            return $this->success('order detail is successfully retrived', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function checkout(MembershipOrderStoreReqeust $request)
    {
        $payload = collect($request->validated());
        $payload['status'] = 'CASH';

        DB::beginTransaction();

        try {
            $member = Member::findOrFail($payload['member_id'])->first()->toArray();

            if ($member['membercard_id'] === null || $member['user_id'] === null) {
                return $this->badRequest('Membership card is not registered', null);
            }

            if ($member['status'] !== 'ACTIVE') {
                return $this->badRequest('Membership status is '.$member['status'], null);
            }

            $user = User::findOrFail($member['user_id'])->first()->toArray();

            if ($user['status'] !== 'ACTIVE') {
                return $this->badRequest('Membership user is '.$user['status'], null);
            }

            $memberCard = MemberCard::findOrFail($member['membercard_id'])->first()->toArray();

            if ($memberCard['status'] !== 'ACTIVE') {
                return $this->badRequest('Membercard is '.$memberCard['status'], null);
            }

            $discount = MemberDiscount::findOrFail($memberCard['discount_id'])->first()->toArray();

            $payload['member_id'] = $member['id'];
            $payload['user_id'] = $user['id'];
            $payload['phone'] = $user['phone'] ? $user['phone'] : null;
            $payload['email'] = $user['email'] ? $user['email'] : null;
            $payload['name'] = $user['name'];
            $payload['card_type'] = $memberCard['label'];
            $payload['card_number'] = $member['member_id'];

            if ($discount['is_fix_amount'] === false) {
                $payload['discount'] = ($payload['amount'] * $discount['discount_percentage'] / 100);
            }

            if ($discount['is_fix_amount'] === true) {
                $payload['discount'] = $discount['discount_fix_amount'];
            }

            if ($discount['is_expend_limit'] === true && $payload['amount'] < $discount['expend_limit']) {
                $payload['discount'] = 0;
            }

            if ($discount['is_expend_limit'] === false && $discount['is_fix_amount'] === true && $payload['amount'] < $discount['discount_fix_amount']) {
                $payload['discount'] = 0;
            }

            $payload['pay_amount'] = $payload['amount'] - $payload['discount'];

            if ($payload['is_wallet'] === true && $payload['pay_amount'] > $member['amount']) {
                return $this->badRequest('insufficient wallet amount', null);
            }

            if ($payload['is_wallet'] === true && $payload['pay_amount'] < $member['amount']) {
                $member['amount'] = $member['amount'] - $payload['pay_amount'];
                $updateMember = Member::findOrFail($member['id'])->update($member);
                $payload['status'] = 'MEMBER_WALLET';
            }

            $order = MemberOrder::create($payload->toArray());

            DB::commit();

            return $this->success('order payment process is successfully completed', $order);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
