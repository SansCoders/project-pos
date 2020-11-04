<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addStock()
    {
        $order = "asc";
        $products = Product::join('stocks', 'products.id', '=', 'stocks.product_id')->orderBy('stock')->paginate(10);
        return view('cashier.addStock', compact('products'));
    }
}
