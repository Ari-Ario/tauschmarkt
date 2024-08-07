<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getProductReviewsBySeller($seller_id)
    {
        // Fetch all products by seller ID
        $products = Product::where('seller_id', $seller_id)->get();

        // Extract product IDs
        $productIds = $products->pluck('id');

        // Fetch product reviews for the products by product IDs
        $fetchProducts = ProductReview::whereIn('product_id', $productIds)->get();
        $allProducts = $fetchProducts->count();
        $averageRating = $fetchProducts->average('rating');

        return response()->json([
            'allProducts' => $allProducts,
            'averageRating' => $averageRating
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeComment(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'comment' => 'required|string|max:1000',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Create a new product comment
            $review = new ProductReview();
            $review->user_id = $request->input('user_id');
            $review->product_id = $request->input('product_id');
            $review->rating = 3; // Set rating to null

            $review->comment = $request->input('comment');
            $review->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Comment submitted successfully',
                'review' => $review
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function storeRating(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            // Create a new product rating
            $review = new ProductReview();
            $review->user_id = $request->input('user_id');
            $review->product_id = $request->input('product_id');
            $review->rating = $request->input('rating');
            $review->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully',
                'review' => $review
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the rating',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Fetch the product reviews along with the user information
        $productReviews = ProductReview::where('product_id', $id)
            ->with('user:id,firstname') // Eager load the user with only id and name
            ->orderBy('created_at', 'desc')
            ->get(['id', 'product_id', 'user_id', 'rating', 'comment', 'created_at', 'updated_at']); // Specify the columns to select

        // Return the reviews as a JSON response
        return response()->json($productReviews);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReview $productReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReview $productReview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReview $productReview)
    {
        // Check if the authenticated user is the owner of the comment
        if (Auth::id() !== $productReview->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this comment'
            ], 403);
        }

        try {
            // Delete the comment
            $productReview->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the comment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
