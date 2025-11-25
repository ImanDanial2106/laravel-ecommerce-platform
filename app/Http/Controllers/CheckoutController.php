<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Str; // <-- IMPORT THIS CLASS TO GENERATE RANDOM STRINGS

class CheckoutController extends Controller
{
    /**
     * Show the checkout/payment page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('storefront.index')->with('error', 'Your cart is empty.');
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $user = Auth::user();
        $customer = Customer::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name]
        );

        return view('checkout.index', compact('cart', 'total', 'customer'));
    }

    /**
     * Process the payment and create the orders.
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('storefront.index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();

        try {
            $customer = Customer::where('email', $user->email)->firstOrFail();
            $customer->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            foreach ($cart as $id => $details) {
                $product = Product::find($id);

                if ($product->stock < $details['quantity']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Sorry, ' . $product->name . ' is out of stock.');
                }

                // --- THIS IS THE KEY LOGIC THAT WAS MISSING ---
                // 1. Generate a unique Order Number
                $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));
                
                // 2. Create the order
                Order::create([
                    'order_number' => $orderNumber, // <-- THIS LINE SAVES IT
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'quantity' => $details['quantity'],
                    'total_price' => $details['price'] * $details['quantity'],
                ]);
                // --- END OF KEY LOGIC ---

                // 3. Deduct the stock
                $product->decrement('stock', $details['quantity']);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'An error occurred. Please try again.');
        }

        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    /**
     * Show the order success/thank you page.
     */
    public function success()
    {
        return view('checkout.success');
    }
}