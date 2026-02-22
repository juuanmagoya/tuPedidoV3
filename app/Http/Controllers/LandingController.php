<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class LandingController extends Controller
{
    public function index(Request $request)
{
    $search = $request->get('search');
    $category = $request->get('category');

    $products = Product::query()
        ->where('status', Product::STATUS_ACTIVE)
        ->when($search, fn ($q) =>
            $q->where('name', 'like', "%{$search}%")
        )
        ->when($category, fn ($q) =>
            $q->where('category_id', $category)
        )
        ->with('category')
        ->get();

    $featured = Product::where('status', Product::STATUS_FEATURED)->take(4)->get();
    $promotions = Product::where('status', Product::STATUS_PROMOTION)->take(4)->get();

    $categories = Category::all();

    return view('welcome', compact(
        'products',
        'featured',
        'promotions',
        'categories'
    ));
}

}
