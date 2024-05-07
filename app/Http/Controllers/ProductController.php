<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Hamcrest\Type\IsNumeric;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'products' => Product::all()
            ]
        ], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'data' => [
                    'success' => true,
                    'product' => $product
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'success' => false,
                    'product' => []
                ]
            ], 200);
        }
    }

    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'errors' => $validator->errors()
                ]
            ], 422);
        }
        Product::create($validator->validated());
        return response()->json([
            'data' => [
                'success' => true,
                'message' => 'Product created successfully',
            ]
        ], 200);
    }
    public function updateProduct(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Invalid product ID. ID must be numeric.',
                ]
            ], 422);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'errors' => $validator->errors()
                ]
            ], 422);
        }
        $produk = Product::where('id', $id)->first();
        if ($produk) {
            Product::where('id', $id)->update($validator->validated());
            return response()->json([
                'data' => [
                    'success' => true,
                    'message' => 'Product updated successfully',
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Product not found',
                ]
            ], 404);
        }
    }
    public function deleteProduct($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Invalid product ID. ID must be numeric.',
                ]
            ], 422);
        }
        $produk = Product::where('id', $id)->first();
        if ($produk) {
            Product::where('id', $id)->delete();
            return response()->json([
                'data' => [
                    'success' => true,
                    'message' => 'Product deleted successfully',
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Product not found',
                ]
            ], 404);
        }
    }
    public function restoreProduct($id)
    {
        if (!is_numeric($id)) {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Invalid product ID. ID must be numeric.',
                ]
            ], 422);
        }
        $produk = Product::onlyTrashed()->where('id', $id)->first();
        if ($produk) {
            Product::onlyTrashed()->where('id', $id)->restore();
            return response()->json([
                'data' => [
                    'success' => true,
                    'message' => 'Product restored successfully',
                ]
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    'success' => false,
                    'message' => 'Product not found',
                ]
            ], 404);
        }
    }
}
