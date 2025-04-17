<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;


class DashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'totalOrders' => Order::count(),
            'totalProducts' => Product::count(),
            'totalCustomers' => User::where('role', 'user')->count(),
        ]);
    }

    public function recentOrders()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user?->name ?? 'Unknown',
                    'image' => url('storage/' . $order->image_url), // Assuming image is stored in the storage folder
                    'date' => $order->created_at->format('Y-m-d H:i'),
                    'status' => $order->status,
                ];
            });
    
        return response()->json($orders);
    }
    
}
