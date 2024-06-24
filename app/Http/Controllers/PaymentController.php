<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
// use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
class PaymentController extends Controller
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
                'address' => $order->user->street,
                'pickupTime' => $order->user->opening, // Adjust based on your DB structure
                'orderDate' => $order->created_at->format('d-m-Y H:i'),
                'image' => $order->user->profile_picture, // Adjust as needed
                'price' => $order->total_price,
                'picked' => $order->pickup,
                'orderCode' => $order->order_code,
            ];
        });

        return response()->json($formattedOrders);
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
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
