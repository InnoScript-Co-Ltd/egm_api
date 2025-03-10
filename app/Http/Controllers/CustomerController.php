<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $customers = Customer::with([''])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Customer list is successfully retrived', $customers);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(CustomerStoreRequest $request){

        $payload=collect($request->validated());
        DB::beginTransaction();
        try{
            $customer=Customer::create($payload->toArray());
            DB::commit();

            return $this->success('Customer is created successfully',$customer);
        }catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id){

       DB::beginTransaction();
       try{
        $customer=Customer::with([]);
        DB::commit();
        return $this->success('customer detail is successfully retrived',$customer);
         }catch (Exception $e) {
            DB::rollback();
            throw $e;
       }
    }

    public function update(CustomerUpdateRequest $request, $id){

        $payload=collect($request->validated());
        DB::beginTransaction();
        try{
            $customer=Customer::findOrFail($id);
            $customer->update($payload->toArray());
            DB::commit();

            return $this->success('Customer is updated successfully',$customer);
        }catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function delete($id){
         DB::beginTransaction();
         try{
            $customer=Customer::findOrFail($id);
            $customer->delete();
            DB::commit();

            return $this->success('Customer is deleted successfully',null);
            }catch (Exception $e) {
            DB::rollback();
            throw $e;
         }
    }
}
