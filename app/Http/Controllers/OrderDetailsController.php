<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;

class OrderDetailsController extends Controller
{
    public function index()
{
    $orders = Order::where('user_id', auth()->id())
                   ->with('order_items.menu')  // Assuming 'orderItems' is the relation to the items and 'menu' fetches menu details
                   ->get();

    return Inertia::render('OrderDetails', [
        'orders' => $orders
    ]);
}

}
