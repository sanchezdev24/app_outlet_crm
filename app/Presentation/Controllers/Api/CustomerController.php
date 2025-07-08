<?php

namespace App\Presentation\Controllers\Api;

use App\Application\Commands\Customer\CreateCustomerCommand;
use App\Application\Commands\Customer\UpdateCustomerCommand;
use App\Application\Commands\Customer\DeleteCustomerCommand;
use App\Application\Queries\Customer\GetCustomersQuery;
use App\Application\Queries\Customer\GetCustomerByIdQuery;
use App\Application\Services\CommandBus;
use App\Application\Services\QueryBus;
use App\Presentation\Requests\Customer\CreateCustomerRequest;
use App\Presentation\Requests\Customer\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus
    ) {}

    public function index(Request $request): JsonResponse
    {
        $query = new GetCustomersQuery(
            $request->input('search'),
            $request->input('type'),
            $request->boolean('active'),
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
            $query = new GetCustomerByIdQuery($id);
            $result = $this->queryBus->execute($query);

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
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

    public function store(CreateCustomerRequest $request): JsonResponse
    {
        try {
            $command = new CreateCustomerCommand(
                $request->name,
                $request->last_name,
                $request->email,
                $request->phone,
                $request->address,
                $request->type,
                $request->birth_date
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Customer created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        try {
            $command = new UpdateCustomerCommand(
                $id,
                $request->name,
                $request->last_name,
                $request->email,
                $request->phone,
                $request->address,
                $request->type,
                $request->birth_date
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Customer updated successfully'
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
            $command = new DeleteCustomerCommand($id);
            $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}