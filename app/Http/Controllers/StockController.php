<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\StockActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addStock(Request $request)
    {
        $order = "asc";
        $products = Product::join('stocks', 'products.id', '=', 'stocks.product_id')->where('product_status', 'show')->orderBy('stock')->paginate(10);
        $compact = ['products'];
        if (isset($request->search)) {
            $cari = $request->search;
            $products = Product::join('stocks', 'products.id', '=', 'stocks.product_id')
                ->where(function ($q) use ($cari) {
                    $q->where('kodebrg', 'LIKE', "%" . $cari . "%")
                        ->Orwhere('nama_product', 'LIKE', "%" . $cari . "%");
                })
                ->where('product_status', 'show')->orderBy('stock')->paginate(10);
            $compact = ['products', 'cari'];
        }
        return view('cashier.addStock', compact($compact));
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

        if ($request->stock_in < 1) {
            return redirect()->back()->with('error', 'not valid');
        }
        $product = Product::where('id', $id)->first();
        if ($product == null) {
            return redirect()->back()->with('error', 'not valid');
        }
        $addStock = Stock::where('product_id', $product->id)->first();
        if ($addStock == null) {
            return redirect()->back()->with('error', 'not valid');
        }

        $tmp_stock = $addStock->stock;
        $addStock->stock = $addStock->stock + $request->get('stock_in');
        $save = $addStock->save();
        if ($save) {
            $stock_a = new StockActivity([
                'stocks_id' => $addStock->id,
                'product_id' => $product->id,
                'users_id' => Auth::user()->id,
                'user_type_id' => 2,
                'type_activity' => 4,
                'stock' => $request->stock_in
            ]);
            $stock_a->save();
            return redirect()->back()->with('message', 'stock berhasil ditambah');
        }
        return redirect()->back();
    }
}
