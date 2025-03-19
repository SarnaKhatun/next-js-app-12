<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $products  = Product::where('user_id', $user_id)->get();
        return response()->json([
           'status' => true,
           'products' => $products,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
               'errors' => $validator->fails(),
            ], 400);
        }

        $validator['user_id'] = Auth::user()->id;
        if ($request->hasFile('banner_image')) {
            $validator['banner_image'] =$request->file('banner_image')->store('products', 'public');
        }

        Product::create($validator);

        return response()->json([
            'status' => true,
            'message' => 'Product Data Created Successfully',
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'status' => true,
            'message' => 'Product data found',
            'product' => $product
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($request->hasFile('banner_image')) {
            Storage::disk('public')->delete($product->banner_image);
        }

        $validator['banner_image'] = $request->file('banner_image')->store('products', 'public');
        $product->update($validator);

        return response()->json([
            'status' => true,
            'message' => 'Product Data Updated Successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product Data Deleted Successfully',
        ], 200);
    }
}
