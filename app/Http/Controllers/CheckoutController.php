<?php

namespace App\Http\Controllers;

use Stripe\StripeClient;
use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
// use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    //
    public function store(Request $request)
    {
        $user = $request->user();
        $carts = $request->carts;
        $products = $request->products;
        $sellerId = $request->seller_id;
        $mergedData = [];
    
        // Loop through the "carts" array and merge with "products" data
        foreach ($carts as $cartItem) {
            foreach ($products as $product) {
                if ($cartItem["id"] == $product["id"]) {
                    // Merge the cart item with product data
                    $mergedData[] = array_merge($cartItem, ["name" => $product["name"], 'price' => $product['price']]);
                }
            }
        }
    
        // Stripe payment
        $stripe = new \Stripe\StripeClient('sk_test_51PRVFGCFV0u7TeyeT35q849Bj5Z20yEOr2EoFcRvJyW7ELi7BmxiDfzPhcggYibOAqCIoal1J0vuHX0iJ3RVVFnL00o4IPXSbH');
        $lineItems = [];
        foreach ($mergedData as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'CHF',
                    'product_data' => [
                        'name' => $item['name'],
                    ],
                    'unit_amount' => (int)($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }
    
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
        ]);
    

        // $newAddress = $request->address;
        // if ($newAddress['address1'] != null) {
        //     $address = UserAddress::where('isMain', 1)->count();
        //     if ($address > 0) {
        //         $address = UserAddress::where('isMain', 1)->update(['isMain' => 0]);
        //     }
        //     $address = new UserAddress();
        //     $address->address1 = $newAddress['address1'];
        //     $address->state = $newAddress['state'];
        //     $address->zipcode = $newAddress['zipcode'];
        //     $address->city = $newAddress['city'];
        //     $address->country_code = $newAddress['country_code'];
        //     $address->type = $newAddress['type'];
        //     $address->user_id = Auth::user()->id;
        //     $address->save();
        // }
        // $mainAddress = $user->user_address()->where('isMain', 1)->first();
        if ($user) {
            $order = new Order();
            $order->status = 'unpaid';
            $order->total_price = $request->total;
            $order->session_id = $checkout_session->id;
            $order->user_id = $user->id;
            $order->seller_id = $sellerId;

            // $order->created_by = $user->id;
            // If a main address with isMain = 1 exists, set its id as customer_address_id
            // $order->user_address_id = $mainAddress->id;
            $order->save();
            $cartItems = CartItem::where(['user_id' => $user->id])->get();
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id, // Assuming you have an 'id' field in your orders table
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price, // You may adjust this depending on your logic
                ]);
                $product = Product::findOrFail($cartItem->product_id); // Retrieve the product by the cart item's product ID
                $totalQuantity = $product->quantity;
                $product->quantity = $totalQuantity - $cartItem->quantity; // Decrement the product quantity by the cart item's quantity
                $product->save(); // Save the updated product quantity

                $cartItem->delete();
                //remove cart items from cookies
                $cartItems = Cart::getCookieCartItems();
                foreach ($cartItems as $item) {
                    unset($item);
                }
                array_splice($cartItems, 0, count($cartItems));
                Cart::setCookieCartItems($cartItems);
            }

            $paymentData = [
                'order_id' => $order->id,
                'amount' => $request->total,
                'status' => 'pending',
                'type' => 'stripe',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'seller_id' => $sellerId,
                // 'session_id' => $session->id
            ];

            Payment::create($paymentData);
        } else {
            // Handle guest user
            $order = new Order();
            $order->status = 'unpaid';
            $order->total_price = $request->total;
            $order->session_id = $checkout_session->id;
            $order->user_id = 1; // Default user ID for guests
            $order->seller_id = $sellerId;

            $order->save();
            $cartItems = CartItem::where(['user_id' => 1])->get();
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                ]);

                // Update product quantity
                $product = Product::findOrFail($cartItem->product_id);
                $totalQuantity = $product->quantity;
                $product->quantity = $totalQuantity - $cartItem->quantity;
                $product->save();

                $cartItem->delete();

                // Remove cart items from cookies
                $cartItems = Cart::getCookieCartItems();
                foreach ($cartItems as $item) {
                    unset($item);
                }
                array_splice($cartItems, 0, count($cartItems));
                Cart::setCookieCartItems($cartItems);
            }

            $paymentData = [
                'order_id' => $order->id,
                'amount' => $request->total,
                'status' => 'pending',
                'type' => 'stripe',
                'created_by' => 1, // Default user ID for guests
                'updated_by' => 1, // Default user ID for guests
                'seller_id' => $sellerId,
            ];

            Payment::create($paymentData);
        }
        
    
        return response()->json(['url' => $checkout_session->url]);
    }
    

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey('sk_test_51PRVFGCFV0u7TeyeT35q849Bj5Z20yEOr2EoFcRvJyW7ELi7BmxiDfzPhcggYibOAqCIoal1J0vuHX0iJ3RVVFnL00o4IPXSbH');
        $sessionId = $request->get('session_id');
        $user = $request->user();

        // dd($sessionId);
        // return redirect('/');

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new \Exception('Session not found');
            }
            
            $order = Order::where('session_id', $sessionId)->first();
            // dd($order);
            if (!$order) {
                throw new \Exception('Order not found');
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }
            if ($user) {
            return redirect('/dashboard');

            } else {
                return redirect('/');
            }
            
            // return response()->json('checkedout');

        } catch (\Exception $e) {
            \Log::error('Stripe error: ' . $e->getMessage());
            abort(500, 'Error processing payment.');
        }
    }
}
