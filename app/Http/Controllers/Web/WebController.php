<?php

namespace App\Http\Controllers\Web;

use App\Traits\JsonResponder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class WebController extends BaseController
{
    use AuthorizesRequests, JsonResponder, ValidatesRequests;
}
