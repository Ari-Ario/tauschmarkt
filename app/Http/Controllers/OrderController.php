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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $orderId = $request->input('orderId');
        $order = Order::find($orderId);
    
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    
        $seller = User::find($order->seller_id);
    
        if (!$seller) {
            return response()->json(['message' => 'Seller not found'], 404);
        }
    
        $stripeSecretKey = config('services.stripe.secret');
        Log::info('Stripe API Key:', ['key' => $stripeSecretKey]);
        \Stripe\Stripe::setApiKey($stripeSecretKey);
    
        try {
            // Update the Stripe Connect Account to request transfers capability
            $account = \Stripe\Account::update(
                $seller->stripe_connect_id,
                [
                    'capabilities' => [
                        'transfers' => ['requested' => true],
                    ],
                ]
            );
    
            if ($account->capabilities->transfers !== 'active') {
                return response()->json(['message' => 'The account does not have the transfers capability enabled.'], 400);
            }
    
            // Create the transfer
            $transfer = \Stripe\Transfer::create([
                'amount' => $order->total_price * 100, // amount in cents
                'currency' => 'chf',
                'destination' => $seller->stripe_connect_id,
                'source_type' => 'card',
                'transfer_group' => 'ORDER_' . $order->id,
            ]);
    
            $order->pickup = now();
            $order->save();
    
            return response()->json(['message' => 'Order marked as collected and payout successful.'], 200);
    
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error:', [
                'message' => $e->getMessage(),
                'code' => $e->getStripeCode(),
                'httpStatus' => $e->getHttpStatus(),
                'request' => $e->getRequestId(),
                'error' => $e->getError()
            ]);
            return response()->json(['message' => 'Error processing payout: ' . $e->getMessage()], 400);
        } catch (\Exception $e) {
            Log::error('General error:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Error processing payout: ' . $e->getMessage()], 500);
        }
    }

    public function generatePdf($orderId)
    {
        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Eager load the products related to the order items
        $cartItems = OrderItem::where('order_id', $order->id)->with('product:id,name,price')->get();

        // Transform cartItems to include product details directly
        $cartItems = $cartItems->map(function ($item) {
            return [
                'quantity' => $item->quantity,
                'product_id' => $item->product_id,
                'unit_price' => $item->unit_price,
                'name' => $item->product->name,
                'price' => $item->product->price,
            ];
        });        
        // return($cartItems);

        // Generate PDF
        $pdf = PDF::loadView('pdf.bill', ['order' => $order, 'items' => $cartItems]);
        $pdfPath = 'bills/bill_' . $order->id . '.pdf';
        Storage::put('public/' . $pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);

        return response()->json(['pdfUrl' => $pdfUrl]);
    }

    public function getOrderCountByUser($user_id)
    {
        // Count the number of orders for the user
        $orderCount = Order::where('seller_id', $user_id)->count();

        return response()->json([
            'orderCount' => $orderCount
        ]);
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
