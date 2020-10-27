<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Product;
use App\Stock;
use App\StockActivity;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDO;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllProducts()
    {
        $products = Product::paginate(10)->sortByDesc("created_at");
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
        $user = Auth::user();
        $request->validate([
            // 'pCategory' => 'numeric|required|min:3',
            'pKode' => 'required|min:3',
            'pNama' => 'required|min:3|max:90',
            'pStok' => 'required|numeric',
            'imgproduct' => 'mimes:jpeg,png|max:1014',
        ]);

        if ($request->hasFile('imgproduct')) {

            $extension = $request->imgproduct->extension();
        } else {
            $pathimg = 'product-img/default-img-product.png';
        }
        $product = new Product([
            'category_id' => $request->pCategory,
            'kodebrg' => $request->pKode,
            'nama_product' => $request->pNama,
            'price' => $request->pPrice,
            'img' => $pathimg,
            'description' => $request->pDescription,
            'unit_id' => $request->pUnit,
        ]);
        $savepoduct = $product->save();

        if ($savepoduct) {
            //simpan stock
            $stock = new Stock([
                'product_id' => $product->id,
                'stock' => $request->pStok
            ]);
            $s_stock = $stock->save();
            if ($s_stock) {
                //log stock
                $a_stock = new StockActivity([
                    'stocks_id' => $stock->id,
                    'product_id' => $product->id,
                    'users_id' => $user->id,
                    'user_type_id' => 2,
                    'type_activity' => 1,
                    'stock' => $request->pStok
                ]);
                $a_stock->save();
                return redirect()->back()->with('success', 'Product Added');
            }
        } else {
            return redirect()->back()->with('error', 'nope! ');
        }
        // dd($product);
    }
}
