<?php

namespace App\Presentation\Controllers\Api;

use App\Application\Queries\Product\GetBrandsQuery;
use App\Application\Services\QueryBus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BrandController extends Controller
{
    public function __construct(
        private QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetBrandsQuery(
            $request->boolean('active')
        );

        $result = $this->queryBus->execute($query);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}