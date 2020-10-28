<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addStock()
    {
        $products = Product::paginate(10)->sortByDesc("stock");
        return view('cashier.addStock', compact('products'));
    }
}
