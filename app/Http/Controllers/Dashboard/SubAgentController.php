<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\SubAgentStoreRequest;
use App\Http\Requests\SubAgentUpdateRequest;
use App\Models\SubAgent;
use Exception;
use Illuminate\Support\Facades\DB;

class SubAgentController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $subAgents = SubAgent::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('sub agent list is successfully retrived', $subAgents);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(SubAgentStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

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

            $subAgent = SubAgent::create($payload->toArray());

            DB::commit();

            return $this->success('New sub agent is created successfully', $subAgent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $subAgent = SubAgent::findOrFail($id);
            DB::commit();

            return $this->success('Sub agent info is retrived successfully retrived', $subAgent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(SubAgentUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $subAgent = SubAgent::findOrFail($id);

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

            $subAgent->update($payload->toArray());

            DB::commit();

            return $this->success('Sub agent is updated successfully', $subAgent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $subAgent = SubAgent::findOrFail($id);
            $subAgent->delete();
            DB::commit();

            return $this->success('Sub agent is deleted successfully', $subAgent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
