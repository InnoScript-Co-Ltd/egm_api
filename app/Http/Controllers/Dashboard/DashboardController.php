<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AgentTypeEnum;
use App\Models\Agent;
use App\Models\Partner;

class DashboardController extends Controller
{
    public function counts()
    {
        $partners = Partner::count();
        $agnets = Agent::all();

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
        ]);
    }
}
