<?php

namespace App\Http\Controllers;

use App\Traits\JsonResponser;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="News aggregator API documentation"
 * )
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, JsonResponser, ValidatesRequests;
}
