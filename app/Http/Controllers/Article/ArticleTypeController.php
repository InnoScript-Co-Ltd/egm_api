<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ArticleType\ArticleTypeStoreRequest;
use App\Http\Requests\ArticleType\ArticleTypeUpdateRequest;
use App\Models\ArticleType;
use Exception;

class ArticleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $articleTypes = ArticleType::active()
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Article lists successfully retrived', $articleTypes);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleTypeStoreRequest $request)
    {
        $payload = collect($request->validated());

        try {
            $article = ArticleType::create($payload->toArray());

            return $this->success('Article type created successfully', $article);
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
            $articleType = ArticleType::findOrFail($id);

            return $this->success('Article type retrieved successfully', $articleType);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleTypeUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        try {
            $articleType = ArticleType::findOrFail($id);

            $articleType->update($payload->toArray());

            return $this->success('Article type updated successfully', $articleType);
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
            $articleType = ArticleType::findOrFail($id);

            $articleType->delete();

            return $this->success('Article type deleted successfully');
        } catch (Exception $e) {
            throw $e;
        }
    }
}
