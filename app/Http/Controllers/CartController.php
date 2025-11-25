<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display the contents of the cart.
     */
    public function index()
    {
        // Get the cart from the session
        $cart = session()->get('cart', []);

        // Calculate the total price
        $total = 0;
        if (is_array($cart)) {
            foreach ($cart as $id => $details) {
                $total += $details['price'] * $details['quantity'];
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $product = Product::find($request->product_id);

        // Get the cart from session, or create an empty array
        $cart = session()->get('cart', []);

        // If product is already in cart, increment quantity
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            // If not, add it to the cart
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        // Put the updated cart back into the session
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->product_id])) {
            unset($cart[$request->product_id]); // Remove the item
            session()->put('cart', $cart); // Save the updated cart
        }

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }
}