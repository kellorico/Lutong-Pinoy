<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request){
        $menu = Menu::find();

        if(!$menu){
            return redirect()->back()->with('error', 'Menu item not found.');
        }


    }
}
