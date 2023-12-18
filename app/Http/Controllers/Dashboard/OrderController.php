<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\ExportOrder;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $order = Order::with(['users', 'deliveryAddress'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Order list is successfully retrived', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(OrderStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $paymentType = $payload['payment_type'];
            $deliveryAddressId = $payload['delivery_address_id'];
            $userId = $payload['user_id'];

            $deliveryAddress = DeliveryAddress::findOrFail($deliveryAddressId);
            $user = User::findOrFail($userId);

            $username = $user['name'];
            $phone = $user['phone'];
            $email = $user['email'];

            $address = $deliveryAddress['address'];
            $contact_phone = $deliveryAddress['contact_phone'];
            $contact_person = $deliveryAddress['contact_person'];

            $order = Order::create([
                'delivery_address_id' => $deliveryAddressId,
                'user_id' => $userId,
                'user_name' => $username,
                'phone' => $phone,
                'email' => $email,
                'delivery_address' => $address,
                'delivery_contact_person' => $contact_person,
                'delivery_contact_phone' => $contact_phone,
                'discount' => 1000,
                'delivery_feed' => 1000,
                'total_amount' => 1000,
                'items' => ['kasmdkas'],
                'payment_type' => $paymentType,
            ]);

            DB::commit();

            return $this->success('Order is created successfully', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {

        DB::beginTransaction();
        try {

            $order = Order::findOrFail($id);
            DB::commit();

            return $this->success('Order detail is successfully retrived', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    public function update(OrderUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $paymentType = $payload['payment_type'];
            $deliveryAddressId = $payload['delivery_address_id'];
            $userId = $payload['user_id'];

            $deliveryAddress = DeliveryAddress::findOrFail($deliveryAddressId);
            $user = User::findOrFail($userId);

            $username = $user['name'];
            $phone = $user['phone'];
            $email = $user['email'];

            $address = $deliveryAddress['address'];
            $contact_phone = $deliveryAddress['contact_phone'];
            $contact_person = $deliveryAddress['contact_person'];

            $order = Order::findOrFail($id);
            $order->update([
                'delivery_address_id' => $deliveryAddressId,
                'user_id' => $userId,
                'user_name' => $username,
                'phone' => $phone,
                'email' => $email,
                'delivery_address' => $address,
                'delivery_contact_person' => $contact_person,
                'delivery_contact_phone' => $contact_phone,
                'discount' => 1000,
                'delivery_feed' => 1000,
                'total_amount' => 1000,
                'items' => ['asds,d,as,'],
                'payment_type' => $paymentType,
                'status' => $payload['status'],
            ]);

            DB::commit();

            return $this->success('Order is updated successfully', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $order = Order::findOrFail($id);
            $order->delete($id);
            DB::commit();

            return $this->success('Order is deleted successfully', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function export()
    {
        return Excel::download(new ExportOrder, 'Orders.xlsx');
    }
}
