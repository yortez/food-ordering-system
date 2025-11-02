<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Guest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $products = Product::with('category')->get();
        $guests = Guest::orderBy('name')->get();



        return view('pos.index', compact('categories', 'products', 'guests'));
    }

    public function getProducts($categoryId = null)
    {
        $query = Product::with('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->get();

        return response()->json([
            'products' => $products
        ]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:cash,card,digital_wallet,bank_transfer',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $taxRate = 0.12; // 12% tax rate
            $subProfit = 0;

            // Calculate subtotal
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $product->price * $item['quantity'];
                $subProfit += $product->cost * $item['quantity'];
            }

            $discountAmount = $request->discount_amount ?? 0;
            $taxAmount = ($subtotal - $discountAmount) * $taxRate;
            $totalAmount = $subtotal - $discountAmount + $taxAmount;
            $profit = $totalAmount - $subProfit;

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)) . '-' . time(),
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'notes' => $request->notes,
                'profit' => $profit,
                'completed_at' => now(),
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $product->price * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            // Create guest only if it doesn't already exist
            if ($request->filled('customer_name')) {
                // Try to find an existing guest by name, phone, or email
                $existingGuest = \App\Models\Guest::query()
                    ->where('name', $request->customer_name)
                    ->when($request->customer_phone, fn($q) => $q->orWhere('phone', $request->customer_phone))
                    ->when($request->customer_email, fn($q) => $q->orWhere('email', $request->customer_email))
                    ->first();

                if (!$existingGuest) {
                    // Only create if not found
                    $newGuest = \App\Models\Guest::create([
                        'name' => $request->customer_name,
                        'phone' => $request->customer_phone ?? null,
                        'email' => $request->customer_email ?? null,
                    ]);
                } else {
                    // Optional: use existing guest ID if needed
                    $newGuest = $existingGuest;
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'order' => $order->load('orderItems.product'),
                'message' => 'Order created successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReceipt($orderId)
    {
        $order = Order::with('orderItems.product')->findOrFail($orderId);

        return view('pos.receipt', compact('order'));
    }

    public function getOrderHistory()
    {
        $orders = Order::with('orderItems.product')
            ->where('order_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return response()->json([
            'orders' => $orders
        ]);
    }
}
