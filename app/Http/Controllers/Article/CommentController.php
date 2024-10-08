<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\Storage;

const COMMENT_IMAGE_FOLDER = 'comments';

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $comments = Comment::active()
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Comment lists successfully retrived', $comments);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request)
    {
        $payload = collect($request->validated());

        try {
            if (! empty($payload['photos'])) {
                $commentPhotos = collect($payload['photos'])->map(function ($commentPhoto) {
                    $commentPhotoPath = Storage::disk('public')->putFile(COMMENT_IMAGE_FOLDER, $commentPhoto);

                    return explode('/', $commentPhotoPath)[1];
                });

                $payload->put('photos', $commentPhotos);
            }

            $comment = Comment::create($payload->toArray());

            return $this->success('Comment created successfully', $comment);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            return $this->success('Comment retrieved successfully', $comment);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        try {
            $comment = Comment::findOrFail($id);

            if (! empty($payload['photos'])) {

                // delete existing images
                foreach ($comment->photos as $commentPhoto) {
                    $commentPhotoPath = COMMENT_IMAGE_FOLDER.'/'.$commentPhoto;

                    if (Storage::disk('public')->exists($commentPhotoPath)) {
                        Storage::disk('public')->delete($commentPhotoPath);
                    }
                }

                // store new images
                $commentPhotos = collect($payload['photos'])->map(function ($commentPhoto) {
                    $commentPhotoPath = Storage::disk('public')->putFile(COMMENT_IMAGE_FOLDER, $commentPhoto);

                    return explode('/', $commentPhotoPath)[1];
                });

                $payload->put('photos', $commentPhotos);
            }

            $comment->update($payload->toArray());

            return $this->success('Comment updated successfully', $comment);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            // delete images
            foreach ($comment->photos as $commentPhoto) {
                $commentPhotoPath = COMMENT_IMAGE_FOLDER.'/'.$commentPhoto;

                if (Storage::disk('public')->exists($commentPhotoPath)) {
                    Storage::disk('public')->delete($commentPhotoPath);
                }
            }

            $comment->delete();

            return $this->success('Comment deleted successfully');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
