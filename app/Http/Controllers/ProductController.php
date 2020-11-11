<?php

namespace App\Http\Controllers;

use AboutUs;
use App\CategoryProduct;
use App\Faktur;
use App\Keranjang;
use App\Product;
use App\Receipts_Transaction;
use App\Stock;
use App\StockActivity;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use PDO;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInfoProduct($id)
    {
        $constCompany = DB::table('about_us')->first();
        $getProduct = Product::where('id', $id)->first();
        $categors = CategoryProduct::where('id', $getProduct->category_id)->first();
        $categories = CategoryProduct::get();
        $units = Unit::get();
        $unitss = Unit::where('id', $getProduct->unit_id)->first();
        $stock = Stock::where('id', $getProduct->id)->first();
        if ($getProduct == null) {
            return json_encode('error');
        }

        return view('cashier.productDetail', compact(['getProduct', 'stock', 'categories', 'categors', 'unitss', 'units', 'constCompany']));
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
    public function getAllUnits()
    {
        $units = Unit::paginate(10);
        return view('admin.units', compact('units'));
    }

    public function searchProduct(Request $request)
    {
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::all();
        $cari = $request->get('cari');
        $products = Product::where('nama_product', 'LIKE', '%' . $cari . '%')->get();
        if (count($products) > 0) {
            return view('home', compact(['products', 'categories', 'cart', 'cekTransactions', 'cari']));
        } else {
            return view('home', compact(['products', 'categories', 'cart', 'cekTransactions']))->with('message', 'maaf, tidak menemukan yang dicari');
        }
    }

    public function updateProduct(Request $request)
    {
        $products = Product::find($request->id);
        $request->validate([
            'pNama' => 'required|min:3|max:90',
            'imgproduct' => 'mimes:jpeg,png|max:1014'
        ]);

        if ($request->hasFile('imgproduct')) {
            $gambar = $request->file('imgproduct');
            $new_gambar = $products->kodebrg . '_' . time() . $gambar->getClientOriginalName();
            $gambars = 'product-img/' . $new_gambar;
            $lokasi_gambar = public_path('/product-img');
            $gmbr = Image::make($gambar->path());
            $gmbr->resize(735, 552)->save($lokasi_gambar . '/' . $new_gambar);
        } else {
            $gambars = $products->img;
        };

        $slife = DB::table('products')->where('id', $request->id)->update([
            'category_id' => $request->pCategory,
            'nama_product' => $request->pNama,
            'price' => $request->pPrice,
            'img' => $gambars,
            'description' => $request->pDescription,
            'slug' => Str::slug($request->pNama)
        ]);
        if ($slife) {
            return redirect()->route('cashier.products')->with('success', 'Product Telah Di Perbaharui');
        }
    }

    public function storeProduct(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'pKode' => 'required|min:3|unique:products,kodebrg',
            'pNama' => 'required|min:3|max:90',
            'pStok' => 'required|numeric',
            'imgproduct' => 'mimes:jpeg,png|max:1014',
        ], [
            'pKode.unique' => 'kode sudah digunakan, silahkan gunakan kode lain'
        ]);

        if ($request->hasFile('imgproduct')) {
            $gambar = $request->file('imgproduct');
            $new_gambar = $request->pKode . '_' . time() . $gambar->getClientOriginalName();
            $lokasi_gambar = public_path('/product-img');
            $gmbr = Image::make($gambar->path());
            $gmbr->resize(735, 552)->save($lokasi_gambar . '/' . $new_gambar);
        } else {
            $new_gambar = 'default-img-product.png';
        }
        $product = new Product([
            'category_id' => $request->pCategory,
            'kodebrg' => strtoupper($request->pKode),
            'nama_product' => $request->pNama,
            'price' => $request->pPrice,
            'img' => 'product-img/' . $new_gambar,
            'description' => $request->pDescription,
            'unit_id' => $request->pUnit,
            'slug' => Str::slug($request->pNama)
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
    }

    public function detailsProduct($slug)
    {
        $constCompany = DB::table('about_us')->first();
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $data = Product::where('slug', $slug)->get();
        if ($data->count() > 0) {
            return view('product-overview', compact('data', 'cart', 'cekTransactions', 'constCompany'));
        } else {
            return redirect()->back()->with('error', 'product not found');
        }
    }

    public function addToCart(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'valbuy' => 'required|min:1|numeric',
            'dataproduct' => 'required|numeric',
        ]);
        $data = Product::where('id', $request->dataproduct)->get();
        foreach ($data as $p) {
            if (!isset($p->stocks->stock)) {
                return redirect()->back()->with('error', 'stock tidak tersedia');
            }
            if ($request->valbuy > $p->stocks->stock) {
                return redirect()->back()->with('error', 'melebihi persediaan barang');
            }
        }
        if ($data->count() == 0) return redirect()->back()->with('error', 'data tidak valid');
        $exist_cart = Keranjang::where('user_id', $user->id)->where('product_id', $request->dataproduct)->first();
        if ($exist_cart != null) {
            Keranjang::where('user_id', $user->id)->where('product_id', $request->dataproduct)
                ->update(['buy_value' => ($exist_cart->buy_value + $request->valbuy)]);
            return redirect()->back()->with('success_added', 'Berhasil ditambah ke keranjang');
        }
        $cart = new Keranjang([
            'user_id' => $user->id,
            'product_id' => $request->dataproduct,
            'buy_value' => $request->valbuy
        ]);
        if ($cart->save()) {
            return redirect()->back()->with('success_added', 'Berhasil ditambah ke keranjang');
        } else {
            dd($data);
        }
    }

    public function editQtyCart(Request $request)
    {
        $idCart = $request->idCart;
        $cart = Keranjang::where('id', $idCart)->first();
        if (!$cart || $cart == null) return "not valid!";
        $checkProduct = Product::where('id', $cart->product_id)->first();
        if (!$checkProduct || $checkProduct == null) return "not valid!";


        return view('another.formEditCartQty', compact(['cart', 'checkProduct']));
    }
    public function editQtyCart_put(Request $request, $id)
    {
        $request->validate(['buy_value' => 'required|numeric']);

        //validasi
        $cart = Keranjang::where('id', $id)->first();
        if (!$cart || $cart == null) return "not valid!";
        $vStock = Stock::where('product_id', $cart->product_id)->first();
        if (!$vStock || $vStock == null) return "not valid!";

        $buy = $request->buy_value;
        if ($buy > $vStock->stock) return redirect()->back()->with('error', 'melebihi stock yang ada');

        $update = Keranjang::where('id', $id)->update([
            'buy_value' => $buy
        ]);
        if ($update) {
            return redirect()->back()->with('success', "berhasil di update");
        } else {
            return redirect()->back()->with('fail', "gagal di update");
        }
    }

    public function checkOutProducts()
    {
        $constCompany = DB::table('about_us')->first();
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        return view('checkout', compact('cart', 'cekTransactions', 'constCompany'));
    }

    public function processCheckOut()
    {
        $cart = Keranjang::where('user_id', Auth::user()->id)->with('product')->get();
        $buyer = Auth::user();
        foreach ($cart as $item) {
            $cekStock = Stock::where('product_id', $item->product_id)->first();
            if ($item->buy_value > $cekStock->stock) return redirect()->back()->with('err', 'Transaksi Gagal! Pembelian Produk ' . $item->product->nama_product . ' melebihi stock yang tersedia, yaitu ' . $cekStock->stock . ' ' . $item->product->unit->unit);
            $products_id[] = $item->product_id;
            $products_list[] = $item->product->nama_product;
            $products_price[] = $item->product->price;
            $products_totalPrice[] = $item->product->price * $item->buy_value;
            $buy_values[] = $item->buy_value;
        }

        $latestOrder = Receipts_Transaction::orderBy('created_at', 'DESC')->first();
        if ($latestOrder == null) {
            $latestOrder = 0;
        } else {
            $latestOrder = $latestOrder->count();
        }
        $cekFaktur = Faktur::orderBy('created_at', 'desc')->first();
        if ($cekFaktur == null) {
            $fakNum = 0;
        } else {
            $fakNum = $cekFaktur->faktur_number;
        }
        $receipt = new Receipts_Transaction([
            'transaction_id' => date('Ymd') . $buyer->id . str_pad($latestOrder + 1, 5, "0", STR_PAD_LEFT),
            'user_id' => $buyer->id,
            'user_name' => $buyer->name,
            'cashier_id' => null,
            'cashier_name' => null,
            'products_id' => json_encode($products_id),
            'products_list' => json_encode($products_list),
            'products_buyvalues' => json_encode($buy_values),
            'products_prices' => json_encode($products_price),
            'type' => 1,
            'is_done' => 0,
            'done_time' => null,
            'total_productsprices' => json_encode($products_totalPrice),
            'order_via' => 2
        ]);
        $sreceipt = $receipt->save();
        if ($sreceipt) {
            foreach ($products_id as $p_i) {
                $changeStock = Stock::where('product_id', $p_i)->first();
                if ($changeStock != null) {
                    $scart = Keranjang::where('product_id', $p_i)->first();
                    $us = Stock::where('product_id', $p_i)->update(['stock' => ($changeStock->stock - $scart['buy_value'])]);
                    if ($us) {
                        $a_stock = new StockActivity([
                            'stocks_id' => $changeStock->id,
                            'product_id' => $p_i,
                            'users_id' => $buyer->id,
                            'user_type_id' => 3,
                            'type_activity' => 2,
                            'stock' => $scart['buy_value']
                        ]);
                        $a_stock->save();
                    }
                }
            }

            $faktur = new Faktur([
                'order_id' => $receipt->transaction_id,
                'faktur_number' => ($fakNum + 1),
            ]);
            $faktur->save();
            Keranjang::where('user_id', Auth::user()->id)->delete();
            return redirect()->back()->with('success', 'Berhasil dikirim ke kasir, silahkan menunggu untuk diproses');
        } else {
            dd($receipt);
        }
    }

    public function destroyItemFromCheckout($id)
    {
        $item = Keranjang::find($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted!');
    }
}
