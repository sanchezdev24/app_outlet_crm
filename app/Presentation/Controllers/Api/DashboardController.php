<?php

namespace App\Presentation\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Infrastructure\Persistence\Eloquent\Models\Customer;
use App\Infrastructure\Persistence\Eloquent\Models\Product;
use App\Infrastructure\Persistence\Eloquent\Models\Sale;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $monthlySales = Sale::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)
                           ->count();
        $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->sum('final_total');

        return response()->json([
            'success' => true,
            'data' => [
                'totalCustomers' => $totalCustomers,
                'totalProducts' => $totalProducts,
                'monthlySales' => $monthlySales,
                'monthlyRevenue' => number_format($monthlyRevenue, 2)
            ]
        ]);
    }
}