<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileStoreRequest;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $file = File::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('file list are successfully retrived', $file);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(FileStoreRequest $request)
    {
        $payload = collect($request->validated());
        $file = $payload['file'];

        $image_path = $file->store('images', 'public');
        $name = explode('/', $image_path)[1];

        DB::beginTransaction();
        try {
            $file = File::create([
                'name' => $name,
                'category' => $payload['category'],
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ]);
            DB::commit();

            return $this->success('file is created successfully', $file);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $file = File::FindOrFail($id);
            $image = file_get_contents(public_path('storage')."/images/$file->name");
            DB::commit();

            return response($image, 200)->header('Content-Type', $file->type);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $file = File::findOrFail($id);
            $file->delete($id);
            DB::commit();

            return $this->success('File is deleted', $file);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
