<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'phone' => 'required',
            'total' => 'numeric',
            'coupon_code' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $discount = 0;

        // Apply coupon if provided
        if ($request->coupon_code) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();

            if (!$coupon || !$coupon->isValid()) {
                return response()->json(['message' => 'Invalid or expired coupon.'], 400);
            }

            if ($coupon->min_order_amount && $request->total < $coupon->min_order_amount) {
                return response()->json(['message' => 'Order does not meet minimum amount for this coupon.'], 400);
            }

            $discount = $coupon->type === 'fixed'
                ? $coupon->value
                : ($request->total * $coupon->value / 100);

            $discount = min($discount, $request->total);
            $coupon->increment('used_count');
        }

        // Handle image upload
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageUrl = $image->store('images', 'public');
        }

        // Ensure the user is authenticated
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Create the order
        $order = Order::create([
            'user_id' => $userId, // ✅ Add user ID
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'status' => $request->status ?? 'Pending',
            'total' => $request->total - $discount,
            'coupon_code' => $request->coupon_code,
            'discount' => $discount,
            'image_url' => $imageUrl,
        ]);

        return response()->json($order, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);
    
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
    
        return response()->json(['message' => 'Order status updated successfully.']);
    }
    

    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(['message' => 'Order deleted']);
    }

    public function myOrders()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json(
            Order::where('user_id', $user->id)->orderByDesc('created_at')->get()
        );
    }
}
