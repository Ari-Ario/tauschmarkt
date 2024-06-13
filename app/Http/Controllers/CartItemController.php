<?php

namespace App\Http\Controllers;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemController extends Controller
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

    public function view(Request $request, Product $product)
    {
       
        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
            if ($cartItems->count() > 0) {
                return Inertia::render(
                    'User/CartList',
                    [
                        'cartItems' => $cartItems,
                        'userAddress' => $userAddress
                    ]
                );
            } 
            
        }
        else {
            $cartItems = Cart::getCookieCartItems();
            if (count($cartItems) > 0) {
                $cartItems = new CartResource(Cart::getProductsAndCartItems());
                return  Inertia::render('User/CartList', ['cartItems' => $cartItems]);
            } else {
                return redirect()->back();
            }
        }
    }
    public function store(Request $request)
    {

        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $request->id, 'seller_id' => $request->seller_id])->first();
            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'seller_id' => $request->seller_id,
                    'product_id' => $request->id,
                    'quantity' => 1,
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as $item) {
                if ($item['product_id'] === $request->id) {
                    $item['quantity'] += $quantity;
                    $isProductExists = true;
                    break;
                }
            }

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'seller_id' => $request->seller_id,
                    'product_id' => $request->id,
                    'quantity' => $quantity,
                    'price' => $request->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return response()->json(['flash' => ['success' => 'Product added to cart successfully!']]);
    }



    public function update(Request $request, Product $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return  response()->json(['flash' => ['success' => 'Product updated in cart successfully!']]);
    }


    public function delete(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id, 'seller_id' => $product->seller_id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return response()->json(['flash' => ['info' => 'Product is not in cart!']]);
            } else {
                return response()->json(['flash' => ['success' => 'Product deleted from cart successfully!']]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return response()->json(['flash' => ['info' => 'Product is not in cart!']]);
            } else {
                return response()->json(['flash' => ['success' => 'Product deleted from cart successfully!']]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        //
    }
}
