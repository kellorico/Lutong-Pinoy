<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $menus = Menu::with('category')->get();
    $categories = Category::all();
    return Inertia::render('MenuItems', [
        'menus' => $menus,
        'categories' => $categories,
        'user_id' => auth()->id(),
    ]);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();
        return inertia('Admin/Menu', [
            'menus' => $menus,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $menu = $request->validate([
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'menu_name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric',
            'availability' => 'required|string',
        ]);


        $image_path = $request->hasFile('image_path')
            ? $request->file('image_path')->store('img/menus', 'public') : null;

        Menu::create([
            'image_path' => $image_path,
            'menu_name' => $menu['menu_name'],
            'category_id' => $menu['category_id'],
            'description' => $menu['description'],
            'price' => $menu['price'],
            'availability' => $menu['availability'],
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'menu_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:500',
            'price' => 'required|numeric',
            'availability' => 'required|in:available,unavailable',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the menu instance
        $menu = Menu::findOrFail($id);

        // Check if a new image is uploaded
        if ($request->hasFile('image_path')) {
            // Delete the old image if it exists
            if ($menu->image_path && Storage::exists($menu->image_path)) {
                Storage::delete($menu->image_path);
            }

            // Store the new image and update the path
            $menu->image_path = $request->file('image_path')->store('menu_images', 'public');
        }

        // Assign other data to model fields
        $menu->menu_name = $validated['menu_name'];
        $menu->category_id = $validated['category_id'];
        $menu->description = $validated['description'];
        $menu->price = $validated['price'];
        $menu->availability = $validated['availability'];

        // Save the instance
        $menu->save();

        // Redirect back with success message
        return back()->with('success', 'Menu updated successfully.');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the menu instance
        $menu = Menu::findOrFail($id);

        // Check if the menu has an associated image
        if ($menu->image_path && Storage::exists($menu->image_path)) {
            // Delete the image from storage
            Storage::delete($menu->image_path);
        }

        // Delete the menu record from the database
        $menu->delete();

        // Redirect back with success message
        return back()->with('success', 'Menu deleted successfully.');
    }
}
