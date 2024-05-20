<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\Invoice;
use Exception;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $invoice = Invoice::with('order')
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Invoice list is successfully retrived', $invoice);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(InvoiceStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $invoice = Invoice::create($payload->toArray());
            DB::commit();

            return $this->success('Invoice is created successfully', $request);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $invoice = Invoice::with('order')
                ->findOrFail($id);
            DB::commit();

            return $this->success('Invoice details is retrived successfully', $invoice);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(InvoiceUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            $invoice = Invoice::findOrFail($id);
            $invoice->update($payload->toArray());

            DB::commit();

            return $this->success('Invoice is updated successfully', $invoice);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $invoice = Invoice::findOrFail($id);
            $invoice->delete();
            DB::commit();

            return $this->success('Invoice is deleted successfully', $invoice);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
