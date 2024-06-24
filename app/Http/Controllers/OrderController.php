<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Product;
// use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getOrdersSeller($seller_id)
    {
        // Fetch orders by seller_id and include user information
        $orders = Order::where('seller_id', $seller_id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the response to include user information
        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'firstname' => $order->user->firstname,
                'lastname' => $order->user->lastname,
                'street' => $order->user->street,
                'house_number' => $order->user->house_number,
                'zip_code' => $order->user->zip_code,
                'city' => $order->user->city,
                'opening' => $order->user->opening, 
                'closing' => $order->user->closing, 
                'orderDate' => $order->created_at->format('d-m-Y H:i'),
                'image' => $order->user->profile_picture, // Adjust as needed
                'price' => $order->total_price,
                'picked' => $order->pickup,
                'orderCode' => $order->order_code,
            ];
        });

        return response()->json($formattedOrders);
    }

    public function getOrdersUser($user_id)
    {
        // Fetch orders by user_id, include user and seller information, and order by created_at in descending order
        $orders = Order::where('user_id', $user_id)
            ->with(['user', 'seller'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Format the response to include seller information
        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'firstname' => $order->seller->firstname,
                'lastname' => $order->seller->lastname,
                'street' => $order->seller->street,
                'house_number' => $order->seller->house_number,
                'zip_code' => $order->seller->zip_code,
                'city' => $order->seller->city,
                'opening' => $order->seller->opening, 
                'closing' => $order->seller->closing,                 
                'orderDate' => $order->created_at->format('d-m-Y H:i'),
                'profile_image' => $order->seller->profile_picture,
                'enterprise_image' => $order->seller->enterprise_picture,
                'price' => $order->total_price,
                'picked' => $order->pickup,
                'orderCode' => $order->order_code,
            ];
        });

        return response()->json($formattedOrders);
    }

    public function markAsCollected(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'orderId' => 'required|exists:orders,id',
        ]);

        // Find the order by ID
        $order = Order::findOrFail($request->orderId);

        // Update the pickup column with the current time
        $order->pickup = now();
        $order->save();

        // Return a success response
        return response()->json(['message' => 'Order marked as collected successfully.'], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
