<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ArticleLike\ArticleLikeStoreRequest;
use App\Models\ArticleLike;
use Exception;

class ArticleLikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articleLikes = ArticleLike::with(['articleType', 'article', 'comment'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Article like lists successfully retrived', $articleLikes);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleLikeStoreRequest $request)
    {
        $payload = collect($request->validated());

        try {
            $articleLike = ArticleLike::create($payload->toArray());
            $articleLike->load(['articleType', 'article', 'comment']);

            return $this->success('Article like created successfully', $articleLike);
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
            $articleLike = ArticleLike::findOrFail($id);
            $articleLike->load(['articleType', 'article', 'comment']);

            return $this->success('Article like retrieved successfully', $articleLike);
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
            $articleLike = ArticleLike::findOrFail($id);

            $articleLike->delete();

            return $this->success('Article like deleted successfully');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
