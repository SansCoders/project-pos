<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Faktur;
use App\Keranjang;
use App\Prices_Custom;
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


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getInfoProduct($id)
    {
        $constCompany = DB::table('about_us')->first();
        if ($constCompany == null) return redirect()->back();

        $product = Product::where('id', $id)->where('product_status', 'show')->first();
        if ($product == null) return redirect()->back();
        $categories = CategoryProduct::get();
        return view('cashier.productDetail', compact(['product', 'categories']));
    }

    public function getAllProducts()
    {
        $products = Product::where('product_status', 'show')->paginate(10);
        $categories = CategoryProduct::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        if (Auth::guard('admin')->check()) {
            return view('admin.products', compact('products', 'units'));
        } elseif (Auth::guard('cashier')->check()) {
            return view('cashier.products', compact(['products', 'categories', 'units']));
        }
    }
    public function getAllUnits()
    {
        $units = Unit::where('status', 1)->paginate(10);
        return view('admin.units', compact('units'));
    }

    public function searchProducts(Request $request)
    {
        $cari = $request->search;
        $products = Product::where('product_status', 'show')->where('kodebrg', 'LIKE', "%$cari%")->orWhere('nama_product', 'LIKE', "%{$cari}%")
            ->paginate(10);
        $categories = CategoryProduct::where('status', 1)->get();
        $units = Unit::where('status', 1)->get();
        return view('cashier.products', compact(['products', 'categories', 'units', 'cari']));
    }

    public function searchProduct(Request $request)
    {
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->where('status', 'pending')->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 3)->get();
        $categories = CategoryProduct::all();
        $cari = $request->get('cari');
        $products = Product::where('nama_product', 'LIKE', '%' . $cari . '%')->where('product_status', 'show')->get();
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
            'pPrice' => 'required|numeric',
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
        return redirect()->back()->with('error', 'kesalahan');
    }

    public function storeProduct(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'pKode' => 'required|min:3|unique:products,kodebrg',
            'pNama' => 'required|min:3|max:90',
            'pStok' => 'required|numeric',
            'pPrice' => 'required|numeric',
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
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->where('status', 'pending')->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 3)->get();
        $data = Product::where('slug', $slug)->where('product_status', 'show')->get();
        if ($data->count() > 0) {
            return view('product-overview', compact('data', 'cart', 'cekTransactions', 'constCompany'));
        } else {
            return redirect()->back()->with('error', 'product not found');
        }
    }
    public function addToCart(Request $request)
    {
        $user = Auth::user();
        if (Auth::guard('admin')->check()) {
            $user_type = 1;
        } elseif (Auth::guard('cashier')->check()) {
            $user_type = 2;
        } elseif (Auth::guard('web')->check()) {
            $user_type = 3;
        } else {
            $user_type = 4;
        }
        $request->validate([
            'valbuy' => 'required|min:1|numeric',
            'dataproduct' => 'required|numeric',
        ]);
        $data = Product::where('id', $request->dataproduct)->first();
        // foreach ($data as $p) {
        if (!isset($data->stocks->stock)) {
            return redirect()->back()->with('error', 'stock tidak tersedia');
        }
        if ($request->valbuy > $data->stocks->stock) {
            return redirect()->back()->with('error', 'melebihi persediaan barang');
        }
        // }
        if ($data == null) return redirect()->back()->with('error', 'data tidak valid');
        $customPrice = Prices_Custom::where('product_id', $request->dataproduct)->where('user_id', $user->id)->where('user_type', $user_type)->first();
        if ($customPrice != null) {
            $cp_product = $customPrice->prices_c;
        } else {
            $cp_product = $data->price;
        }
        $exist_cart = Keranjang::where('user_id', $user->id)->where('product_id', $request->dataproduct)->where('user_type', $user_type)->first();
        if ($exist_cart != null) {
            if (($request->valbuy + $exist_cart->buy_value) > $data->stocks->stock) {
                return redirect()->back()->with('error', 'melebihi persediaan barang');
            }
            // if (($request->valbuy + $exist_cart->buy_value) > $exist_cart->buy_value) {
            //     $cp_product = $cp_product * ($request->valbuy + $exist_cart->buy_value);
            // } elseif(($request->valbuy + $exist_cart->buy_value) < $exist_cart->buy_value) {
            //     $cp_product = $cp_product * ($request->valbuy + $exist_cart->buy_value);
            // }
            Keranjang::where('user_id', $user->id)->where('product_id', $request->dataproduct)->where('user_type', $user_type)
                ->update([
                    'buy_value' => ($exist_cart->buy_value + $request->valbuy),
                    'custom_price' => $cp_product * ($exist_cart->buy_value + $request->valbuy)
                ]);
            return redirect()->back()->with('success', 'Berhasil ditambah ke keranjang');
        }
        $cart = new Keranjang([
            'user_id' => $user->id,
            'product_id' => $request->dataproduct,
            'buy_value' => $request->valbuy,
            'user_type' => $user_type,
            'custom_price' => $cp_product * $request->valbuy
        ]);
        if ($cart->save()) {
            return redirect()->back()->with('success_added', 'Berhasil ditambah ke keranjang');
        } else {
            return redirect()->back();
        }
    }

    public function editPriceReceipt(Request $request)
    {
        $transaction = Receipts_Transaction::where('transaction_id', $request->idReceipt)->first();
        if ($transaction == null) {
            return "kesalahan!";
        }
        $product_id = $request->idProduct;
        $checkProduct = Product::where('id', $product_id)->first();
        if (!$checkProduct || $checkProduct == null) return "not valid!";
        $p_id = json_decode($transaction->products_id);
        $tp_prices = json_decode($transaction->total_productsprices);
        foreach ($p_id as $index => $pid) {
            if ($pid == $product_id) {
                $previousTotalPrice = $tp_prices[$index];
                return view('another.formEditReceiptPrice', compact(['product_id', 'transaction', 'previousTotalPrice', 'checkProduct']));
            }
        }
        return "kesalahan!";
        // $idCart = $request->idCart;
        // $cart = Keranjang::where('id', $idCart)->first();
        // if (!$cart || $cart == null) return "not valid!";
        // $checkProduct = Product::where('id', $cart->product_id)->first();
        // if (!$checkProduct || $checkProduct == null) return "not valid!";

    }
    public function editPriceReceipt_put(Request $request, $id)
    {
        $Datatransaction = Receipts_Transaction::where('id', $id);
        $customTotalPrice = $request->priceTotalCustom;
        $product_id = $request->idproduct;
        $getDataTransaction = $Datatransaction->first();
        if ($getDataTransaction == null) {
            return "kesalahan!";
        }
        $request->validate(['priceTotalCustom' => 'required|numeric']);

        $p_id = json_decode($getDataTransaction->products_id);
        $tp_prices = json_decode($getDataTransaction->total_productsprices);
        foreach ($p_id as $index => $pid) {
            if ($pid == $product_id) {

                $data[] = (int)$customTotalPrice;
            } else {
                $data[] = $tp_prices[$index];
            }
        }
        $update = $Datatransaction->update([
            'total_productsprices' => json_encode($data)
        ]);
        if ($update) {
            return redirect()->back()->with('success', 'berhasil di ubah');
        } else {
            dd($data);
        }
        //validasi
        // $cart = Keranjang::where('id', $id)->where('user_type', $ut)->first();
        // if (!$cart || $cart == null) return "not valid!";
        // $update = Receipts_Transaction::where('id', $id)->update([
        //     'custom_price' => $request->priceCustom
        // ]);
        // if ($update) {
        //     return redirect()->back()->with('success', "berhasil di update");
        // } else {
        //     return redirect()->back()->with('fail', "gagal di update");
        // }
    }
    public function editPriceCart(Request $request)
    {
        $idCart = $request->idCart;
        $cart = Keranjang::where('id', $idCart)->first();
        if (!$cart || $cart == null) return "not valid!";
        $checkProduct = Product::where('id', $cart->product_id)->first();
        if (!$checkProduct || $checkProduct == null) return "not valid!";

        return view('another.formEditPriceCart', compact(['cart', 'checkProduct']));
    }
    public function editPriceCart_put(Request $request, $id)
    {
        $request->validate(['priceCustom' => 'required|numeric']);
        if (Auth::guard("cashier")->check()) {
            $ut = 2;
        } elseif (Auth::guard("web")->check()) {
            $ut = 3;
        } else {
            return redirect()->back();
        }
        //validasi
        $cart = Keranjang::where('id', $id)->where('user_type', $ut)->first();
        if (!$cart || $cart == null) return "not valid!";
        $update = Keranjang::where('id', $id)->where('user_type', $ut)->update([
            'custom_price' => $request->priceCustom
        ]);
        if ($update) {
            return redirect()->back()->with('success', "berhasil di update");
        } else {
            return redirect()->back()->with('fail', "gagal di update");
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
        $user = Auth::user();
        $request->validate(['buy_value' => 'required|numeric']);
        if (Auth::guard("cashier")->check()) {
            $ut = 2;
        } elseif (Auth::guard("web")->check()) {
            $ut = 3;
        } else {
            return redirect()->back();
        }
        //validasi
        $Keranjang = Keranjang::where('id', $id)->where('user_type', $ut);
        $cart = $Keranjang->first();
        if (!$cart || $cart == null) return "not valid!";
        $vStock = Stock::where('product_id', $cart->product_id)->first();
        if (!$vStock || $vStock == null) return "not valid!";
        $product = Product::where('id', $cart->product_id)->first();
        if (!$product || $product == null) return "not valid!";
        $buy = $request->buy_value;
        if ($buy > $vStock->stock) return redirect()->back()->with('error', 'melebihi stock yang ada');
        // $data = Product::where('id', $cart->product_id)->first();

        // $customPrice = Prices_Custom::where('product_id', $cart->product_id)->where('user_id', $user->id)->where('user_type', $ut)->first();
        // if ($customPrice != null) {
        //     $cp_product = $customPrice->prices_c;
        // } else {
        //     $cp_product = $data->price;
        // }
        $update = $Keranjang->update([
            'buy_value' => $buy,
            'custom_price' => $product->price * $buy
        ]);
        if ($update) {
            return redirect()->back()->with('success', "berhasil di update");
        } else {
            return redirect()->back()->with('error', "gagal di update");
        }
    }

    public function checkOutProducts()
    {
        $constCompany = DB::table('about_us')->first();
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->where('status', 'pending')->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 3)->get();
        return view('checkout', compact('cart', 'cekTransactions', 'constCompany'));
    }

    public function processCheckOut()
    {
        $cart = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 3)->with('product')->get();
        $buyer = Auth::user();
        foreach ($cart as $item) {
            $cekStock = Stock::where('product_id', $item->product_id)->first();
            if ($item->buy_value > $cekStock->stock) return redirect()->back()->with('err', 'Transaksi Gagal! Pembelian Produk ' . $item->product->nama_product . ' melebihi stock yang tersedia, yaitu ' . $cekStock->stock . ' ' . $item->product->unit->unit);
            $products_id[] = $item->product_id;
            $products_list[] = $item->product->nama_product;
            $products_price[] = $item->product->price;
            $products_totalPrice[] = $item->product->price * $item->buy_value;
            $buy_values[] = $item->buy_value;
            $custom_prices[] = $item->custom_price;
            $diskon_product[] = 0;
        }
        $cek = "user_id = $buyer->id AND order_via = 3 AND date(created_at) = date(now()) Order By created_at DESC";
        $latestOrder = Receipts_Transaction::whereRaw($cek)->get();
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
            'order_via' => 3,
            'diskon' =>  json_encode($diskon_product),
            'custom_prices' => json_encode($custom_prices)
        ]);
        $sreceipt = $receipt->save();
        if ($sreceipt) {
            foreach ($products_id as $p_i) {
                $changeStock = Stock::where('product_id', $p_i)->first();
                if ($changeStock != null) {
                    $scart = Keranjang::where('product_id', $p_i)->where('user_type', 3)->first();
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
            Keranjang::where('user_id', Auth::user()->id)->where('user_type', 3)->delete();
            return redirect()->back()->with('success', 'Berhasil dikirim ke kasir, silahkan menunggu untuk diproses');
        } else {
            return redirect()->back()->with('error', 'tidak bisa diproses, ada kesalahan');
        }
    }

    public function destroyItemFromCheckout($id)
    {
        $item = Keranjang::find($id);
        $item->delete();
        return redirect()->back()->with('success', 'Item deleted!');
    }
    public function destroyTemp($id)
    {
        $product = Product::find($id);
        $product->product_status = 2;
        $product->save();
        return redirect()->back();
    }
}
