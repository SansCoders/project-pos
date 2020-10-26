<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Product;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllProducts()
    {
        $products = Product::paginate(10);
        $categories = CategoryProduct::get();
        $units = Unit::get();
        if (Auth::guard('admin')->check()) {
            return view('admin.products', compact('products', 'units'));
        } elseif (Auth::guard('cashier')->check()) {
            return view('cashier.products', compact(['products', 'categories', 'units']));
        }
    }
    public function storeProduct(Request $request)
    {
        // dd($request);
        $request->validate([
            // 'pCategory' => 'numeric|required|min:3',
            'pKode' => 'required|min:3',
            'pNama' => 'required|min:3|max:90',
            'pStok' => 'required|numeric',
            // 'imgproduct' => 'mimes:jpeg,jpg,png|required|max:10000',
        ]);
        dd($request);
        $product = new Product([
            'category_id' => $request->pCategory,
            'kodebrg' => $request->pKode,
            'nama_product' => $request->pNama,
            'price' => $request->pPrice,
            'img' => $request->imgproduct,
            'description' => $request->pDescription,
            'unit_id' => $request->pUnit,
        ]);
        $product->save();
        // dd($product);
        return redirect()->back()->with('success', 'Product Added');
    }
}
