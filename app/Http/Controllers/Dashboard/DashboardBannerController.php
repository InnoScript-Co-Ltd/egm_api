<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\Dashboard\BannerStoreRequest;
use App\Http\Requests\Dashboard\BannerUpdateRequest;
use App\Models\Banner;
use Exception;
use Illuminate\Support\Facades\DB;

class DashboardBannerController extends Controller
{
    public function index()
    {
        try {
            $banners = Banner::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Banner are retrived successfully', $banners);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $banners = Banner::findOrFail($id);

            return $this->success('banner is retrived successfully', $banners);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(BannerUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {
            $banner = Banner::findOrFail($id);
            if (isset($payload['image'])) {
                $ImagePath = $payload['image']->store('images', 'public');
                $Image = explode('/', $ImagePath)[1];
                $payload['image'] = $Image;
            }
            $banner->update($payload->toArray());
            DB::commit();

            return $this->success('banner is updated successfully', $banner);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(BannerStoreRequest $request)
    {

        $payload = collect($request->validated());
        DB::beginTransaction();

        try {

            if (isset($payload['image'])) {
                $ImagePath = $payload['image']->store('images', 'public');
                $Image = explode('/', $ImagePath)[1];
                $payload['image'] = $Image;
            }
            $banners = Banner::create($payload->toArray());
            DB::commit();

            return $this->success('banner is created successfully', $banners);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $banners = Banner::findOrFail($id);
            $banners->delete();
            DB::commit();

            return $this->success('banners is deleted successfully', $banners);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
