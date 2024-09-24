<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\ArticleType;
use Exception;
use Illuminate\Http\Request;

class PartnerArticleTypeController extends Controller
{
    public function index()
    {
        try {
            $partnerArticleTypes = ArticleType::active()
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->paginationQuery();

            return $this->success('Partner article list retrive successfully', $partnerArticleTypes);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
