<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UploadController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderItemController;
use App\Http\Controllers\API\ProductImageController;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\UserController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
// });


// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
// });



// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/messages', [MessageController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

  });  // Products
    Route::apiResource('products', ProductController::class);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    // Route::post('/orders', [OrderController::class, 'store']);
    // Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/my-orders', [OrderController::class, 'myOrders']);
        Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus']);
        // Laravel route
        Route::put('/users/{id}', [UserController::class, 'update']);

    });

    // File Upload
    Route::post('/upload', [UploadController::class, 'upload']);



Route::apiResource('categories', CategoryController::class);
Route::apiResource('order-items', OrderItemController::class);
Route::apiResource('product-images', ProductImageController::class);


// Route::apiResource('coupons', CouponController::class);
Route::post('coupons/validate', [CouponController::class, 'validateCode']);
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('coupons', CouponController::class);
    // Route::get('stats', [DashboardController::class, 'stats']);
    // Route::get('recent-orders', [DashboardController::class, 'recentOrders']);
});
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::get('/stats', [DashboardController::class, 'stats']);
    Route::get('/recent-orders', [DashboardController::class, 'recentOrders']);
});
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Get all users
    Route::get('/{id}', [UserController::class, 'show']); // Get a single user
    Route::post('/', [UserController::class, 'store']); // Create a new user
    Route::put('/{id}', [UserController::class, 'update']); // Update user role
    Route::delete('/{id}', [UserController::class, 'destroy']); // Delete a user
});