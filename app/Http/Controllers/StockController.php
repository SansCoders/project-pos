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

    public function addStockProduct($id)
    {
        $product = Product::where('id', $id)->first();
        if ($product == null) {
            return redirect()->back()->with('error', 'not valid');
        }

        return view('cashier.product_addStock', compact('product'));
    }

    public function stockIn_store(Request $request, $id)
    {
        $request->validate([
            'stock_in' => 'required|numeric'
        ], [':number' => ' :attribute harus berbentuk angka', ':numeric' => ' :attribute wajib diisi',]);

        $product = Product::where('id', $id)->first();
        if ($product == null) {
            return redirect()->back()->with('error', 'not valid');
        }
        dd($id);
    }
}
