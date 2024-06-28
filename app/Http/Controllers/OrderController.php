<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Stripe\StripeClient;
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
                'user_id' => $order->user->id,
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

        // Retrieve the seller's Stripe account ID
        $seller = User::findOrFail($order->seller_id); // Assuming seller_id is a column in the orders table

        if (!$seller || empty($seller->stripe_account_id || !$seller->payment)) {
            return response()->json(['message' => 'Seller does not have a Stripe account linked.'], 400);
        }

        // Set your Stripe secret key
        $stripe = new StripeClient('sk_test_51PRVFGCFV0u7TeyeT35q849Bj5Z20yEOr2EoFcRvJyW7ELi7BmxiDfzPhcggYibOAqCIoal1J0vuHX0iJ3RVVFnL00o4IPXSbH');

        try {
            $transfer = $stripe->transfers->create([
                'amount' => $order->total_price * 100, // amount in cents
                'currency' => 'chf',
                'destination' => $seller->stripe_account_id,
                'transfer_group' => 'ORDER_' . $order->id,
            ]);
    
            $order->pickup = now();
            $order->save();

            return response()->json(['message' => 'Order marked as collected and payout successful.'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error processing payout: ' . $e->getMessage()], 500);
        }
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
