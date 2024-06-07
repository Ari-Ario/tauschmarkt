<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\ProductImages;
use Illuminate\Support\Str;
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

        $products = Product::with('category', 'product_images');
        $filterProducts = $products->filtered()->paginate(9)->withQueryString();

        $categories = Category::get();

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

        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->seller_id = $request->seller_id;
        $product->save();
        // Product::save($product);


        //check if product has images upload 

        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                ProductImages::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
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
    //update 
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:categories,id',
            // 'brand_id' => 'required|integer|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'product_images.*' => 'nullable|image|max:2048',
            'quantity' => 'nullable|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        try {
            // Find the existing product
            $product = Product::findOrFail($id);
    
            // Update the product fields
            $product->name = $request->name; // Corrected this line
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->description = $request->description;
            $product->category_id = $request->category_id;
            // $product->brand_id = $request->brand_id;
    
            // Handle file uploads if there are images
            if ($request->hasFile('product_images')) {
                // Assuming there's a one-to-many relationship with the ProductImage model
                $product->images()->delete(); // Delete old images if necessary
    
                // Save new images
                $productImages = $request->file('product_images');
                foreach ($productImages as $image) {
                    $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('product_images', $uniqueName, 'public');
    
                    $product->images()->create(['path' => $path]);
                }
            }
    
            // Save the updated product to the database
            $product->save();
    
            return response()->json(['success' => 'Product updated successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating product', 'details' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function deleteImage($id)
    {
        $image = ProductImage::where('id', $id)->delete();
        return redirect()->route('admin.products.index')->with('success', 'Image deleted successfully.');
    }

    function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json(['success' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting product', 'details' => $e->getMessage()], 500);
        }
    }
}
