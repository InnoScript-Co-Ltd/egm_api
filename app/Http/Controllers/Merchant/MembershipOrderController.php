<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\MembershipOrderStoreReqeust;
use App\Models\Member;
use App\Models\MemberCard;
use App\Models\MemberDiscount;
use App\Models\MemberOrder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MembershipOrderController extends Controller
{
    public function checkout(MembershipOrderStoreReqeust $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $user = User::find($payload['user_id'])->get();

            $payload['name'] = $user['name'];
            $payload['phone'] = $user['phone'] ? $user['phone'] : null;
            $payload['email'] = $user['email'] ? $user['email'] : null;

            $member = Member::find($payload['member_id'])->get();
            $payload['card_number'] = $member['member_id'];

            if ($member['status'] !== 'ACTIVE') {
                return $this->badRequest('Member is not acitve', null);
            }

            $memberCard = MemberCard::find($member['membercard_id'])->get();

            $payload['card_type'] = $memberCard['label'];

            if ($memberCard['status'] !== 'ACTIVE') {
                return $this->badRequest('Member card is not acitve', null);
            }

            $discount = MemberDiscount::find($memberCard['discount_id'])->get();

            $order = MemberOrder::create($payload->toArray());
            DB::commit();

            return $this->success('order payment process is successfully completed', $order);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
