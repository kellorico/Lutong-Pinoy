<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,cancelled,in_progress',
            'order_items' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status']
            ]);

            if (!empty($validated['order_items'])) {
                foreach ($validated['order_items'] as $item) {
                    $order->orderItems()->create([
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create order');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled,in_progress',
            'total_amount' => 'required|numeric|min:0',
            'order_items' => 'array'
        ]);

        // Retrieve the order by ID
        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->update([
                'status' => $validated['status'],
                'total_amount' => $validated['total_amount']
            ]);

            $existingItemIds = collect($validated['order_items'])
                ->whereNotNull('id')
                ->pluck('id')
                ->toArray();

            // Delete removed items
            $order->orderItems()->whereNotIn('id', $existingItemIds)->delete();

            foreach ($validated['order_items'] as $item) {
                if (isset($item['id'])) {
                    // Update existing item
                    $orderItem = $order->orderItems()->find($item['id']);
                    if ($orderItem) {
                        $orderItem->update([
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ]);
                    }
                } else {
                    // Add new item
                    $order->orderItems()->create([
                        'menu_id' => $item['menu_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price']
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update order');
        }
    }



    public function index()
    {
        $orders = Order::with(['user', 'order_items.menu'])->get();

        return Inertia::render('Admin/Orders', [
            'orders' => $orders
        ]);
    }

    public function destroy($id){
        $order = Order::findOrFail($id);

        $order->delete();

        return back();
    }
}
