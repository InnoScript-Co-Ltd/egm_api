<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AgentTypeEnum;
use App\Models\Agent;
use App\Models\Deposit;
use App\Models\Partner;
use App\Models\Repayment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function counts()
    {
        $partners = Partner::count();
        $agnets = Agent::all();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $agentDeposit = Deposit::whereNotNull('agent_id')->sum('deposit_amount');
        $partnerDeposit = Deposit::whereNotNull('partner_id')->sum('deposit_amount');

        $repaymentAmount = Repayment::whereBetween('date', [
            $startOfMonth,
            $endOfMonth,
        ])->sum('amount');

        $main_agents = collect($agnets)->filter(function ($agent) {
            if ($agent->agent_type === AgentTypeEnum::MAIN_AGENT->value) {
                return $agent;
            }
        });

        $sub_agents = collect($agnets)->filter(callback: function ($agent) {
            if ($agent->agent_type === AgentTypeEnum::SUB_AGENT->value) {
                return $agent;
            }
        });

        return $this->success('static count are retrived successfully', [
            'partners' => $partners,
            'main_agents' => count($main_agents),
            'sub_agents' => count($sub_agents),
            'agent_deposit' => $agentDeposit,
            'partner_deposit' => $partnerDeposit,
            'repayment_amount' => $repaymentAmount,
        ]);
    }
}
