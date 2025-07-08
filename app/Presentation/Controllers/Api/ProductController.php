<?php

namespace App\Presentation\Controllers\Api;

use App\Application\Commands\Product\CreateProductCommand;
use App\Application\Commands\Product\UpdateProductCommand;
use App\Application\Commands\Product\DeleteProductCommand;
use App\Application\Queries\Product\GetProductsQuery;
use App\Application\Queries\Product\GetProductByIdQuery;
use App\Application\Services\CommandBus;
use App\Application\Services\QueryBus;
use App\Presentation\Requests\Product\CreateProductRequest;
use App\Presentation\Requests\Product\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetProductsQuery(
            $request->input('search'),
            $request->integer('category_id'),
            $request->integer('brand_id'),
            $request->boolean('active'),
            $request->boolean('in_stock'),
            $request->integer('page', 1),
            $request->integer('per_page', 15)
        );

        $result = $this->queryBus->execute($query);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $query = new GetProductByIdQuery($id);
            $result = $this->queryBus->execute($query);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        try {
            $command = new CreateProductCommand(
                $request->name,
                $request->description,
                $request->sku,
                $request->price,
                $request->stock,
                $request->category_id,
                $request->brand_id,
                $request->input('images', []),
                $request->discount_percentage,
                $request->discount_valid_until
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Product created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $command = new UpdateProductCommand(
                $id,
                $request->name,
                $request->description,
                $request->sku,
                $request->price,
                $request->stock,
                $request->category_id,
                $request->brand_id,
                $request->input('images', []),
                $request->discount_percentage,
                $request->discount_valid_until
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Product updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $command = new DeleteProductCommand($id);
            $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}