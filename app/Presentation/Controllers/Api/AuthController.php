<?php

namespace App\Presentation\Controllers\Api;

use App\Application\Commands\Auth\LoginCommand;
use App\Application\Commands\Auth\RegisterCommand;
use App\Application\Services\CommandBus;
use App\Presentation\Requests\Auth\LoginRequest;
use App\Presentation\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        //dd($request)
        try {
            $command = new LoginCommand(
                $request->email,
                $request->password,
                $request->boolean('remember', false)
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Login successful'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $command = new RegisterCommand(
                $request->name,
                $request->email,
                $request->password,
                $request->input('role', 'user')
            );

            $result = $this->commandBus->execute($command);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Registration successful'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ]
        ]);
    }
}