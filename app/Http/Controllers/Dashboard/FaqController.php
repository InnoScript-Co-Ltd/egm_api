<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\FaqStoreRequest;
use App\Http\Requests\FaqUpdateRequest;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $faq = Faq::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Faq list is successfully retrived', $faq);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(FaqStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $faq = Faq::create($payload->toArray());
            DB::commit();

            return $this->success('Faq is created successfully', $faq);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $faq = Faq::findOrFail($id);
            DB::commit();

            return $this->success('Faq detail is successfully retrived', $faq);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(FaqUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $faq = Faq::findOrFail($id);
            $faq->update($payload->toArray());
            DB::commit();

            return $this->success('Faq is updated successfully', $faq);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $faq = Faq::findOrFail($id);
            $faq->delete($id);
            DB::commit();

            return $this->success('Faq is deleted successfully', $faq);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
