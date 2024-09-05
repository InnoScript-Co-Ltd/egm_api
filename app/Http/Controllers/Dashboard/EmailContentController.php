<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\EmailContentStoreRequest;
use App\Models\Country;
use App\Models\EmailContent;
use Exception;
use Illuminate\Support\Facades\DB;

class EmailContentController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $emailContent = EmailContent::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Email contents are retrived successfully', $emailContent);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $email = EmailContent::findOrFail($id);
            DB::commit();

            return $this->success('Email content is retrived successfully', $email);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(EmailContentStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $country = Country::findOrFail($payload['country_id']);
            $payload['country_code'] = $country['country_code'];

            $emailContent = EmailContent::create($payload->toArray());

            DB::commit();

            return $this->success('Email content is created successfully', $emailContent);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(EmailContentStoreRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $emailContent = EmailContent::findOrFail($id);
            $emailContent->update($payload->toArray());

            DB::commit();

            return $this->success('Email content is updated successfully', $emailContent);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $emailContent = EmailContent::findOrFail($id);
            $emailContent->delete();

            DB::commit();

            return $this->success('Email content is deleted successfully', $emailContent);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
