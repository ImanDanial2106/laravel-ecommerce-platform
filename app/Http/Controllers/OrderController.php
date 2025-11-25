<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all orders, and also load the related customer and product data
        $orders = Order::with(['customer', 'product'])->latest()->paginate(10); // Show newest first
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($validated['product_id']);
        $total_price = $product->price * $validated['quantity'];

        $orderData = $validated;
        $orderData['total_price'] = $total_price;

        Order::create($orderData);

        return redirect()->route('orders.index')->with('success', 'Order placed successfully.');
    }

    // --- (We don't need show, edit, or update for this project) ---
    public function show(Order $order) {}
    public function edit(Order $order) {}
    public function update(Request $request, Order $order) {}

    /**
     * Remove the specified resource from storage. (Admin "Cancel Order")
     */
    public function destroy(Order $order)
    {
        // --- THIS IS THE NEW RESTOCK LOGIC ---

        // 1. Find the product related to this order
        $product = $order->product;

        // 2. If the product still exists, add the quantity back to the stock
        if ($product) {
            $product->increment('stock', $order->quantity);
        }

        // 3. Now, delete the order
        $order->delete();
        
        return redirect()->route('orders.index')->with('success', 'Order cancelled and stock has been restocked.');
    }
}