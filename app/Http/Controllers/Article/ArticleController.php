<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articles = Article::active()
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Article lists successfully retrived', $articles);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        $payload = collect($request->validated());

        try {
            if (!empty($payload->photos)) {
                $articlePhotos = collect($payload->photos)->map(function ($articlePhoto) {
                    $articlePhotoPath = $articlePhoto->store('articles', 'public');
                    return explode('/', $articlePhotoPath)[1];
                });

                $payload->put('photos', $articlePhotos);
            }

            $article = Article::create($payload->toArray());
            return $this->success('Article created successfully', $article);
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
            $article = Article::findOrFail($id);

            return $this->success('Article retrieved successfully', $article);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        try {
            $article = Article::findOrFail($id);

            if (!empty($payload->photos)) {

                $article->photos->each(fn($articlePhoto) => $articlePhoto->delete());

                $articlePhotos = collect($payload->photos)->map(function ($articlePhoto) {
                    $articlePhotoPath = $articlePhoto->store('articles', 'public');
                    return explode('/', $articlePhotoPath)[1];
                });

                $payload->put('photos', $articlePhotos);
            }

            $article->update($payload->toArray());
            return $this->success('Article updated successfully', $article);
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
            $article = Article::findOrFail($id);
            
            $article->photos->each(fn($articlePhoto) => $articlePhoto->delete());

            $article->delete();
            return $this->success('Article deleted successfully');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
