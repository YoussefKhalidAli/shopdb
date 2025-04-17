<?php

// app/Http/Controllers/API/ProductImageController.php

namespace App\Http\Controllers\API;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function index()
    {
        return response()->json(ProductImage::with('product')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('product_images', 'public');
        $image = ProductImage::create([
            'product_id' => $request->product_id,
            'image_path' => $path,
        ]);

        return response()->json($image, 201);
    }

    public function destroy(ProductImage $productImage)
    {
        Storage::disk('public')->delete($productImage->image_path);
        $productImage->delete();
        return response()->json(['message' => 'Image deleted']);
    }
}
