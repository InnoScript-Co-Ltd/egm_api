<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;
use App\Models\Agent;
use Illuminate\Support\Facades\DB;
use Exception;

class AgentController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $agents = Agent::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('agent list is successfully retrived', $agents);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(AgentStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

            if (isset($payload['profile'])) {
                $profileImagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $profileImagePath)[1];
                $payload['profile'] = $profileImage;
            }

            if (isset($payload['nrc_front'])) {
                $nrcFrontImagePath = $payload['nrc_front']->store('images', 'public');
                $nrcFrontImage = explode('/', $nrcFrontImagePath)[1];
                $payload['nrc_front'] = $nrcFrontImage;
            }

            if (isset($payload['nrc_back'])) {
                $nrcBackImagePath = $payload['nrc_back']->store('images', 'public');
                $nrcBackImage = explode('/', $nrcBackImagePath)[1];
                $payload['nrc_back'] = $nrcBackImage;
            }

            if (isset($payload['passport_back'])) {
                $passportBackImagePath = $payload['passport_back']->store('images', 'public');
                $passportBackImage = explode('/', $passportBackImagePath)[1];
                $payload['passport_back'] = $passportBackImage;
            }

            if (isset($payload['passport_front'])) {
                $passportFrontImagePath = $payload['passport_front']->store('images', 'public');
                $passportFrontImage = explode('/', $passportFrontImagePath)[1];
                $payload['passport_front'] = $passportFrontImage;
            }

            $agent = Agent::create($payload->toArray());

            DB::commit();

            return $this->success('New agent is created successfully', $agent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $agent = Agent::with(['bankAccounts'])->findOrFail($id);
            DB::commit();

            return $this->success('Agent info is retrived successfully retrived', $agent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(AgentUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $agent = Agent::findOrFail($id);

            if (isset($payload['profile'])) {
                $profileImagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $profileImagePath)[1];
                $payload['profile'] = $profileImage;
            }

            if (isset($payload['nrc_front'])) {
                $nrcFrontImagePath = $payload['nrc_front']->store('images', 'public');
                $nrcFrontImage = explode('/', $nrcFrontImagePath)[1];
                $payload['nrc_front'] = $nrcFrontImage;
            }

            if (isset($payload['nrc_back'])) {
                $nrcBackImagePath = $payload['nrc_back']->store('images', 'public');
                $nrcBackImage = explode('/', $nrcBackImagePath)[1];
                $payload['nrc_back'] = $nrcBackImage;
            }

            // if (isset($payload['passport_back'])) {
            //     $passportBackImagePath = $payload['passport_back']->store('images', 'public');
            //     $passportBackImage = explode('/', $passportBackImagePath)[1];
            //     $payload['passport_back'] = $passportBackImage;
            // }

            // if (isset($payload['passport_front'])) {
            //     $passportFrontImagePath = $payload['passport_front']->store('images', 'public');
            //     $passportFrontImage = explode('/', $passportFrontImagePath)[1];
            //     $payload['passport_front'] = $passportFrontImage;
            // }
            
            $agent->update($payload->toArray());

            DB::commit();

            return $this->success('Agent is updated successfully', $agent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $agent = Agent::findOrFail($id);
            $agent->delete();
            DB::commit();

            return $this->success('Agent is deleted successfully', $agent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
