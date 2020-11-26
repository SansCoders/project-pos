<?php

namespace App\Http\Controllers;

use App\Cashier;
use App\Product;
use App\Receipts_Transaction;
use App\Stock;
use Carbon\Carbon;
use App\Faktur;
use App\Keranjang;
use App\Prices_Custom;
use Illuminate\Http\Request;
use App\StockActivity;
use App\User;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $stockCount = 0;
        $products = Product::where('product_status', 1)->get();
        foreach ($products as $item) {
            $s = Stock::where('product_id', $item->id)->first();
            if ($s->stock == 0) {
                $stockCount += 1;
            }
        }
        // $stockCount = Stock::where('stock', 0)->get();
        $transactionPending = Receipts_Transaction::where('is_done', 0)->where('status', 1)->get();
        return view('cashier.home', compact(['transactionPending', 'stockCount']));
    }
    public function transactionProduct(Request $request)
    {
        $transactions = Receipts_Transaction::orderBy('created_at')->get();
        $compact = ['transactions'];
        if (isset($request->search)) {
            $cari = $request->search;
            if ($cari == null) return redirect()->back();
            $firstCharacter = substr($cari, 0, 1);
            if ($firstCharacter == '#') {
                $cari = str_replace('#', '', $cari);
            }
            $transactions = Receipts_Transaction::orderBy('id', 'DESC')->where('status', 1)->where('is_done', 0)
                ->where(function ($q) use ($cari) {
                    $q->where('transaction_id', 'LIKE', "%$cari%")
                        ->Orwhere('user_name', 'LIKE', "%$cari%");
                })->get();
            $compact = ['transactions', 'cari'];
        }
        return view('cashier.transaction', compact($compact));
    }
    public function newTransaction(Request $request)
    {
        $products = Product::paginate(12);

        if ($request->ajax()) {
            $view = view('another.cashier-productlist', compact('products'))->render();
            return response()->json(['html' => $view]);
        }
        return view('cashier.new-transaction', compact('products'));
    }
    public function SearchinnewTransaction(Request $request)
    {
        $cari = $request->search;
        $products = Product::where('nama_product', 'LIKE', '%' . $cari . '%')->paginate(10);
        if ($request->ajax()) {
            $view = view('another.cashier-productlist', compact('products'))->render();
            return response()->json(['html' => $view]);
        }
        return view('cashier.new-transaction', compact('products'));
    }
    public function getdatalistCartContent(Request $request)
    {
        $data = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 2)->get();
        if (count($data) == 0 || count($data) == null) return "cart kosong";
        return view('another.cartLoadCashier', compact('data'));
    }

    public function getdataSeeProduct(Request $request)
    {
        $the_product = Product::where('id', $request->idp)->first();
        if ($the_product == null) return "not valid";
        return view('another.seeProductonTransactionPanel', compact(['the_product']));
    }
    public function sendDataSeeProduct(Request $request)
    {
        $request->validate([
            'dataproduct' => 'required|integer',
            'valbuy' => 'required|integer'
        ]);

        // dd($request);
    }
    public function processCheckout($orderid)
    {
        $transaction = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->where('status', 1)->first();
        if ($transaction == null) {
            return redirect()->route('cashier.transaction');
        }

        return view('cashier.confirmationCheckout', compact('transaction'));
    }

    public function confirmCheckout(Request $request, $orderid)
    {
        $d_cashier = Auth::user();
        $request->validate([
            '_orderid' => 'required',
            'check_approve' => 'accepted'
        ]);
        if ($orderid != $request->_orderid) {
            return redirect()->back();
        }
        $receipt = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->where('status', 1)->first();
        if ($receipt == null) {
            return redirect()->route('cashier.transaction')->with('error', 'tidak valid!');
        }

        $confirm = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->update([
            'cashier_id' => $d_cashier->id,
            'cashier_name' => $d_cashier->name,
            'is_done' => 1,
            'done_time' => date("Y-m-d h:i:s", time()),
            'status' => 2
        ]);

        if (!$confirm) {
            return redirect()->back()->with('error', 'gagal konfirmasi, cek kembali datanya!');
        }
        $retur['products_id'] = json_decode($receipt->products_id);
        $retur['buy_value'] = json_decode($receipt->products_buyvalues);
        foreach ($retur['products_id'] as $index => $a) {
            $data = Stock::where('product_id', $a)->first();
            if ($data != null) {
                $a_stock = new StockActivity([
                    'stocks_id' => $data->id,
                    'product_id' => $a,
                    'users_id' => Auth::user()->id,
                    'user_type_id' => 2,
                    'type_activity' => 3,
                    'stock' => $retur['buy_value'][$index]
                ]);
                $a_stock->save();
            }
        }
        return redirect()->route('cashier.transaction')->with('success', 'order id #' . $orderid . ' berhasil diproses')->with('id_t', $receipt->transaction_id);
    }
    public function confirmCheckoutviaCashier(Request $request)
    {
        $d_cashier = Auth::user();

        $request->validate([
            'user_type' => 'required',
        ], [
            'user_type.required' => 'nama customer wajib diisi'
        ]);


        $now = Carbon::now();
        $cek = "date(created_at) = date(now()) Order By created_at DESC";
        $latestOrder = Receipts_Transaction::whereRaw($cek)->get();
        if ($latestOrder == null) {
            $latestOrder = 0;
        } else {
            $latestOrder = $latestOrder->count();
        }
        // dd($latestOrder);
        $cekFaktur = Faktur::orderBy('created_at', 'desc')->first();
        if ($cekFaktur == null) {
            $fakNum = 0;
        } else {
            $fakNum = $cekFaktur->faktur_number;
        }

        if (!isset($request->user_type)) return redirect()->back()->with("error", 'tidak ada info buyer');
        if ($request->user_type == "cns") {
            if (isset($request->buyer_name)) {
                $buyer_id = $d_cashier->id;
                $buyer_name = $request->buyer_name;
                $r = date('Ymd') . $d_cashier->id . "2" . str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT);
            } else {
                return redirect()->back()->with("error", 'tidak ada info buyer');
            }
        } elseif ($request->user_type == "cr") {
            if (isset($request->user_registered)) {
                $cekUser = User::where('id', $request->user_registered)->first();
                if (!$cekUser || $cekUser == null) return redirect()->back();
                $buyer_name = $cekUser->name;
                $buyer_id = $cekUser->id;
                $r = date('Ymd') . $buyer_id . str_pad($latestOrder + 1, 5, "0", STR_PAD_LEFT);
            } else {
                return redirect()->back()->with("error", 'tidak ada info buyer');
            }
        } else {
            return redirect()->back()->with("error", 'tidak ada info buyer');
        }
        if (!isset($buyer_name)) {
            return redirect()->back()->with("error", 'tidak ada info buyer');
        }
        $cart = Keranjang::where('user_id', $d_cashier->id)->where('user_type', 2)->get();
        foreach ($cart as $item) {
            $cekStock = Stock::where('product_id', $item->product_id)->first();
            if ($item->buy_value > $cekStock->stock) return redirect()->back()->with('error', 'Transaksi Gagal! Pembelian Produk ' . $item->product->nama_product . ' melebihi stock yang tersedia, yaitu ' . $cekStock->stock . ' ' . $item->product->unit->unit);
            $products_id[] = $item->product_id;
            $products_list[] = $item->product->nama_product;
            $products_price[] = $item->product->price;
            $products_totalPrice[] = $item->product->price * $item->buy_value;
            $buy_values[] = $item->buy_value;
            $custom_prices[] = $item->custom_price;
            $diskon_product[] = 0;
        }
        $receipt = new Receipts_Transaction([
            'transaction_id' => $r,
            'user_id' => $buyer_id,
            'user_name' => $buyer_name,
            'cashier_id' => $d_cashier->id,
            'cashier_name' => $d_cashier->name,
            'products_id' => json_encode($products_id),
            'products_list' => json_encode($products_list),
            'products_buyvalues' => json_encode($buy_values),
            'products_prices' => json_encode($products_price),
            'type' => 1,
            'is_done' => 1,
            'done_time' => $now,
            'total_productsprices' => json_encode($products_totalPrice),
            'order_via' => 2,
            'diskon' =>  json_encode($diskon_product),
            'custom_prices' => json_encode($custom_prices),
            'status' => 2
        ]);
        $sreceipt = $receipt->save();
        if ($sreceipt) {
            foreach ($products_id as $p_i) {
                $changeStock = Stock::where('product_id', $p_i)->first();
                if ($changeStock != null) {
                    $scart = Keranjang::where('product_id', $p_i)->where('user_id', $d_cashier->id)->where('user_type', 2)->first();
                    $us = Stock::where('product_id', $p_i)->update(['stock' => ($changeStock->stock - $scart['buy_value'])]);
                    if ($us) {
                        $a_stock = new StockActivity([
                            'stocks_id' => $changeStock->id,
                            'product_id' => $p_i,
                            'users_id' => $d_cashier->id,
                            'user_type_id' => 2,
                            'type_activity' => 3,
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
            Keranjang::where('user_id', $d_cashier->id)->where('user_type', 2)->delete();
            return redirect()->route('cashier.transaction')->with('success', 'order id #' . $receipt->transaction_id . ' berhasil diproses')->with('id_t', $receipt->transaction_id);
        } else {
            return redirect()->back()->with('error', 'tidak bisa diproses, ada kesalahan');
        }
        // for ($i = 0; $i < count($idproduct); $i++) {
        //     $cekProduct = Product::where('id', $idproduct[$i])->first();
        //     if (!$cekProduct || $cekProduct == null) return false;
        //     $cekStock = Stock::where('product_id', $cekProduct->id)->first();

        //     if ($bv_product[$i] > $cekStock->stock || $bv_product[$i] == 0)
        //         return response()->json([
        //             'status' => 'error',
        //             'msg' => "pembelian produk " . $cekProduct->nama_product . " melebihi stock yang tersedia"
        //         ]);
        //     $int_productId[] = (int)$idproduct[$i];
        //     $name_product[] = $cekProduct->nama_product;
        //     $products_prices[] = $cekProduct->price;
        //     $total_productsprices[] = $cekProduct->price * (int)$bv_product[$i];
        //     $int_buyvalue[] = (int)$bv_product[$i];
        // }

        // $latestOrder = Receipts_Transaction::orderBy('created_at', 'DESC')->first();
        // if ($latestOrder == null) {
        //     $latestOrder = 0;
        // } else {
        //     $latestOrder = $latestOrder->count();
        // }
        // $cekFaktur = Faktur::orderBy('created_at', 'desc')->first();
        // if ($cekFaktur == null) {
        //     $fakNum = 0;
        // } else {
        //     $fakNum = $cekFaktur->faktur_number;
        // }
        // $r = date('Ymd') . $d_cashier->id . "2" . str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT);
        // $now = Carbon::now();
        // $receipt = new Receipts_Transaction([
        //     'transaction_id' => $r,
        //     'user_id' => $d_cashier->id,
        //     'user_name' => $buyer_name,
        //     'cashier_id' => $d_cashier->id,
        //     'cashier_name' => $d_cashier->name,
        //     'products_id' => json_encode($int_productId),
        //     'products_list' => json_encode($name_product),
        //     'products_buyvalues' => json_encode($int_buyvalue),
        //     'products_prices' => json_encode($products_prices),
        //     'type' => 1,
        //     'is_done' => 1,
        //     'done_time' => $now->toDateTimeString(),
        //     'total_productsprices' => json_encode($total_productsprices),
        //     'order_via' => 2,
        //     'status' => 2
        // ]);
        // $save = $receipt->save();
        // if ($save) {
        //     $i = 0;
        //     foreach ($int_productId as $p_i) {
        //         $changeStock = Stock::where('product_id', $p_i)->first();
        //         if ($changeStock != null) {
        //             $us = Stock::where('product_id', $p_i)->update(['stock' => ($changeStock->stock - $int_buyvalue[$i])]);
        //             if ($us) {
        //                 $a_stock = new StockActivity([
        //                     'stocks_id' => $changeStock->id,
        //                     'product_id' => $p_i,
        //                     'users_id' => $d_cashier->id,
        //                     'user_type_id' => 2,
        //                     'type_activity' => 2,
        //                     'stock' => $int_buyvalue[$i]
        //                 ]);
        //                 $a_stock->save();
        //             }
        //         }
        //         $i++;
        //     }

        //     $faktur = new Faktur([
        //         'order_id' => $receipt->transaction_id,
        //         'faktur_number' => ($fakNum + 1),
        //     ]);
        //     $faktur->save();
        //     Keranjang::where('user_id', Auth::user()->id)->where('user_type', 2)->delete();
        //     $num_padded = sprintf("%08d", $faktur->faktur_number);
        //     return response()->json([
        //         'status' => 'success',
        //         'noorder' => $receipt->transaction_id,
        //         'nofaktur' => $num_padded,
        //         'msg' => 'Transaksi berhasil dilakukan dengan nomor order #' . $receipt->transaction_id
        //     ]);
        // }
    }


    public function listTransactions()
    {
        $transaction = Receipts_Transaction::orderBy('id', 'DESC')->paginate(10);
        return view('cashier.reports_listTransactions', compact(['transaction']));
    }
    public function searchTransactions(Request $request)
    {
        $cari = $request->search;
        if ($cari == null) return redirect()->back();
        $firstCharacter = substr($cari, 0, 1);
        if ($firstCharacter == '#') {
            $cari = str_replace('#', '', $cari);
        }
        $transaction = Receipts_Transaction::orderBy('id', 'DESC')
            ->where(function ($q) use ($cari) {
                $q->where('transaction_id', 'LIKE', "%$cari%")
                    ->Orwhere('user_name', 'LIKE', "%$cari%")
                    ->Orwhere('cashier_name', 'LIKE', "%$cari%");
            })->get();
        return view('cashier.reports_listTransactions', compact(['transaction', 'cari']));
    }

    public function previewFaktur($id)
    {
        $constCompany = DB::table('about_us')->first();
        $orderId = $id;
        $Receipt = Receipts_Transaction::where('transaction_id', $orderId)->first();
        if ($Receipt == null) {
            return redirect()->back()->with('error', 'tidak valid');
        }
        $pdf = PDF::loadView('invoice', compact(['Receipt', 'constCompany']));
        $num_padded = sprintf("%02d", $Receipt->facktur->faktur_number);
        $filename = "invoice-" . $Receipt->transaction_id . $num_padded . ".pdf";
        $preview = true;
        return view('invoice', compact(['Receipt', 'constCompany', 'preview']));
    }

    public function cetakFaktur(Request $request)
    {
        $constCompany = DB::table('about_us')->first();
        $orderId = $request->orderId;
        $Receipt = Receipts_Transaction::where('id', $orderId)->first();
        if ($Receipt == null) {
            return redirect()->back()->with('error', 'tidak valid');
        }
        $pdf = PDF::loadView('invoice', compact(['Receipt', 'constCompany']));
        $num_padded = sprintf("%02d", $Receipt->facktur->faktur_number);
        $filename = "invoice-" . $Receipt->transaction_id . $num_padded . ".pdf";
        return $pdf->download($filename);
    }
    public function canceledCheckout($id)
    {
        $Receipt = Receipts_Transaction::find($id);
        if ($Receipt == null) return redirect()->back();
        $retur['products_id'] = json_decode($Receipt->products_id);
        $retur['buy_value'] = json_decode($Receipt->products_buyvalues);

        foreach ($retur['products_id'] as $index => $a) {
            $data = Stock::where('product_id', $a)->first();
            if ($data != null) {
                // dd($retur['buy_value'][$index]);
                Stock::where('product_id', $a)->update(['stock' => ($data->stock + $retur['buy_value'][$index])]);
                $a_stock = new StockActivity([
                    'stocks_id' => $data->id,
                    'product_id' => $a,
                    'users_id' => Auth::user()->id,
                    'user_type_id' => 2,
                    'type_activity' => 1,
                    'stock' => $retur['buy_value'][$index]
                ]);
                $a_stock->save();
            }
        }
        $Receipt->status = 3;
        $Receipt->save();
        return redirect()->route('cashier.transaction')->with('canceled', 'Transaksi #' . $Receipt->transaction_id . ' di batalkan');
    }
    public function deleteItemCart(Request $request)
    {
        Keranjang::where('id', $request->id)->delete();
        return response()->json(['status' => 'deleted!' . $request->id]);
    }

    public function getdataReceipts(Request $request)
    {
        $idReceipt = $request->idReceipts;
        $dataReceipt = Receipts_Transaction::where('id', $idReceipt)->first();
        if (!$dataReceipt || $dataReceipt == null) {
            $data = "kosong";
        }
        return view('another.showDetailsRecipts', compact('dataReceipt'));
    }

    public function customPrice()
    {
        $sales = User::where('status', 1)->get();
        return view('cashier.customPricePage', compact('sales'));
    }
    public function customPriceSearch(Request $request)
    {
        $sales = User::where('status', 1)->where('name', 'LIKE', "%$request->cari%")->get();
        return view('cashier.customPricePage', compact('sales'));
    }
    public function setCustomPrice($user)
    {
        $sales = User::find($user);
        if ($sales == null) return redirect()->back();
        return view('cashier.setCustomPrice', compact('sales'));
    }
    public function confirmCustomPrice(Request $request, $user)
    {
        $sales = User::find($user);
        if ($sales == null) return redirect()->back();

        $request->validate([
            'usHargaKhusus' => 'required|numeric',
            'productid' => 'required|numeric'
        ]);
        $salesId = $sales->id;
        $pid = $request->productid;
        $price_custom = $request->usHargaKhusus;

        $cekData = Prices_Custom::where('user_id', $salesId)->where('user_type', 'user')->where('product_id', $pid)->first();
        if ($cekData == null) {
            $newData = new Prices_Custom([
                'user_id' => $salesId,
                'user_type' => 3,
                'product_id' => $pid,
                'prices_c' => $price_custom
            ]);
            $save = $newData->save();
            if ($save) return redirect()->back()->with('message', 'berhasil');
            return redirect()->back()->with('error', 'gagal');
        }
        return redirect()->back();
    }
    public function editCustomPrice(Request $request, $user)
    {
        $sales = User::find($user);
        if ($sales == null) return redirect()->back();
        $validastionpcid = Prices_Custom::find($request->e_pcid);
        if ($validastionpcid == null) return redirect()->back();

        $request->validate([
            'usHargaKhususEdit' => 'required|numeric',
            'productid' => 'required|numeric',
            'e_pcid' => 'required|numeric'
        ]);

        $salesId = $sales->id;
        $pid = $request->productid;
        $e_pcid = $request->e_pcid;
        $price_custom = $request->usHargaKhususEdit;

        $cekData = Prices_Custom::where('user_id', $salesId)->where('user_type', 'user')->where('product_id', $pid)->first();
        if ($cekData != null) {
            $editData = Prices_Custom::where('id', $e_pcid)->update(['prices_c' => $price_custom]);
            if ($editData) return redirect()->back()->with('message', 'berhasil');
            return redirect()->back()->with('error', 'gagal');
        }
        return redirect()->back();
    }
    public function deleteCustomPrice(Request $request)
    {
        $data = Prices_Custom::find($request->customPid);
        $hapus = $data->delete();
        if ($hapus) return redirect()->back()->with('message', 'harga kembali normal');
        return redirect()->back();
    }

    public function cashierCheckout()
    {
        $data = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 2)->get();
        return view('cashier.cashierChekout', compact('data'));
    }
}
