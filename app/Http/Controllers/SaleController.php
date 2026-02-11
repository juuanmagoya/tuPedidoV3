<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with('order');

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('sold_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('sold_at', '<=', $request->date_to);
        }

        $sales = $query->latest('sold_at')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales'));
    }
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

}
