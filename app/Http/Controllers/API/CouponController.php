<?php

// app/Http/Controllers/API/CouponController.php

namespace App\Http\Controllers\API;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::select('id', 'code', 'type', 'value', 'used_count', 'usage_limit', 'expires_at', 'min_order_amount')
                        ->orderByDesc('id')
                        ->get();
    
        return response()->json($coupons);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric',
            'min_order_amount' => 'nullable|numeric',
            'usage_limit' => 'nullable|integer',
            'expires_at' => 'nullable|date'
        ]);

        $coupon = Coupon::create($request->all());
        return response()->json($coupon, 201);
    }

    public function show(Coupon $coupon)
    {
        return response()->json($coupon);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($request->all());
        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['message' => 'Coupon deleted']);
    }

    // ✅ Validate a coupon code
    public function validateCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['message' => 'Invalid or expired coupon.'], 400);
        }

        return response()->json($coupon);
    }
}
