<?php

namespace App\Presentation\Controllers\Api;

use App\Application\Queries\Product\GetCategoriesQuery;
use App\Application\Services\QueryBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    public function __construct(
        private QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetCategoriesQuery(
            $request->boolean('active'),
            $request->integer('parent_id')
        );

        $result = $this->queryBus->execute($query);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}