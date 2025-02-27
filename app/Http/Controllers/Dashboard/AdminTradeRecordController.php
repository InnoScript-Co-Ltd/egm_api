<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\AdminTradeRecordStoreRequest;
use App\Http\Requests\AdminTradeRecordUpdateRequest;
use App\Models\TradeRecord;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

const TRADE_RECORD_IMAGE_FOLDER = 'trade_records';

class AdminTradeRecordController extends Controller
{
    public function index()
    {
        try {
            $tradeRecords = TradeRecord::active()
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Trade Record lists successfully retrived', $tradeRecords);
        } catch (Exception $e) {
            throw $e;

        }
    }
    public function store(AdminTradeRecordStoreRequest $request)
    {
        $payload = collect($request->validated());

        try {
            if (! empty($payload['photos'])) {
                $Photos = collect($payload['photos'])->map(function ($Photo) {
                    $PhotoPath = Storage::disk('public')->putFile(TRADE_RECORD_IMAGE_FOLDER, $Photo);

                    return explode('/', $PhotoPath)[1];
                });

                $payload->put('photos', $Photos);
            }

            $tradeRecord = TradeRecord::create($payload->toArray());

            return $this->success('Trade Record created successfully', $tradeRecord);
        } catch (Exception $e) {

            throw $e;
        }
    }
    public function show($id)
    {
        try {
            $tradeRecord = TradeRecord::findOrFail($id);

            return $this->success('Trade record retrieved successfully.', $tradeRecord);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update(AdminTradeRecordUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        try {
            $tradeRecord = TradeRecord::findOrFail($id);

            if (! empty($payload['photos'])) {

                // delete existing images
                foreach ($tradeRecord->photos as $tradeRecordPhoto) {
                    $tradeRecordPhotoPath = TRADE_RECORD_IMAGE_FOLDER.'/'.$tradeRecordPhoto;

                    if (Storage::disk('public')->exists($tradeRecordPhotoPath)) {
                        Storage::disk('public')->delete($tradeRecordPhotoPath);
                    }
                }

                // store new images
                $tradeRecordPhotos = collect($payload['photos'])->map(function ($tradeRecordPhoto) {
                    $tradeRecordPhotoPath = Storage::disk('public')->putFile(TRADE_RECORD_IMAGE_FOLDER, $tradeRecordPhoto);

                    return explode('/', $tradeRecordPhotoPath)[1];
                });

                $payload->put('photos', $tradeRecordPhotos);
            }

            $tradeRecord->update($payload->toArray());

            return $this->success('trade Record updated successfully', $tradeRecord);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function destroy($id)
    {
        try {
            $tradeRecord = TradeRecord::findOrFail($id);

            // delete images
            foreach ($tradeRecord->photos as $tradeRecordPhoto) {
                $tradeRecordPhotoPath = TRADE_RECORD_IMAGE_FOLDER.'/'.$tradeRecordPhoto;

                if (Storage::disk('public')->exists($tradeRecordPhotoPath)) {
                    Storage::disk('public')->delete($tradeRecordPhotoPath);
                }
            }

            $tradeRecord->delete();

            return $this->success('Trade record deleted successfully.');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
