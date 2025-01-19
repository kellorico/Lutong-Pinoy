<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();
        $users = User::all();
        $orders = Order::all();

        return inertia('Admin/Dashboard', [
            'menus' => $menus,
            'categories' => $categories,
            'users' => $users,
            'orders' => $orders
        ]);
    }
}
