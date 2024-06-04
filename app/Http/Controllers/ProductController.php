<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Fetch products by seller_id (assuming seller_id is the same as the user's ID)
        $products = Product::where('seller_id', $id)->get();

        // Return the products
        return response()->json(['products' => $products], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:categories,id',
            'seller_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'product_picture' => 'nullable|image|max:2048',
            'published' => 'nullable',
            'inStock' => 'nullable',
            'quantity' => 'nullable',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Handle file upload if there is a photo
            if ($request->hasFile('product_picture')) {
                $path = $request->file('product_picture')->store('product_photos', 'public');
                $request->merge(['photo' => $path]);
            }

            // Create the product
            $product = Product::create($request->all());

            return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error adding product', 'details' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
