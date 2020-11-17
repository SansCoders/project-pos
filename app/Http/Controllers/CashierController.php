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
    public function transactionProduct()
    {
        $transactions = Receipts_Transaction::orderBy('created_at')->get();
        return view('cashier.transaction', compact('transactions'));
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
        $products = Product::where('nama_product', 'LIKE', '%' . $cari . '%')->get();
        if ($request->ajax()) {
            $view = view('another.cashier-productlist', compact('products'))->render();
            return response()->json(['html' => $view]);
        }
        return view('cashier.new-transaction', compact('products'));
    }
    public function getdatalistCartContent(Request $request)
    {
        $data = Keranjang::where('user_id', Auth::user()->id)->where('user_type', 2)->get();
        // if ($request->data == null) return "cart kosong";
        // $data = $request->data;
        // return view('another.cartLoadCashier', compact('data'));
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

        dd($request);
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
            'productsId.*' => 'required|integer',
            'buyvalue.*' => 'required|integer',
            'buyer_name' => 'required|min:3|string'
        ], [
            'buyer_name.required' => 'nama buyer wajib diisi'
        ]);
        $idproduct = $request->productsId;
        $bv_product = $request->buyvalue;
        $buyer_name = $request->buyer_name;
        for ($i = 0; $i < count($idproduct); $i++) {
            $cekProduct = Product::where('id', $idproduct[$i])->first();
            if (!$cekProduct || $cekProduct == null) return false;
            $cekStock = Stock::where('product_id', $cekProduct->id)->first();

            if ($bv_product[$i] > $cekStock->stock || $bv_product[$i] == 0)
                return response()->json([
                    'status' => 'error',
                    'msg' => "Stock yang tersedia kurang"
                ]);
            $int_productId[] = (int)$idproduct[$i];
            $name_product[] = $cekProduct->nama_product;
            $products_prices[] = $cekProduct->price;
            $total_productsprices[] = $cekProduct->price * (int)$bv_product[$i];
            $int_buyvalue[] = (int)$bv_product[$i];
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
        $r = date('Ymd') . $d_cashier->id . "2" . str_pad($latestOrder + 1, 4, "0", STR_PAD_LEFT);
        $now = Carbon::now();
        $receipt = new Receipts_Transaction([
            'transaction_id' => $r,
            'user_id' => $d_cashier->id,
            'user_name' => $buyer_name,
            'cashier_id' => $d_cashier->id,
            'cashier_name' => $d_cashier->name,
            'products_id' => json_encode($int_productId),
            'products_list' => json_encode($name_product),
            'products_buyvalues' => json_encode($int_buyvalue),
            'products_prices' => json_encode($products_prices),
            'type' => 1,
            'is_done' => 1,
            'done_time' => $now->toDateTimeString(),
            'total_productsprices' => json_encode($total_productsprices),
            'order_via' => 2,
            'status' => 2
        ]);
        $save = $receipt->save();
        if ($save) {
            $i = 0;
            foreach ($int_productId as $p_i) {
                $changeStock = Stock::where('product_id', $p_i)->first();
                if ($changeStock != null) {
                    $us = Stock::where('product_id', $p_i)->update(['stock' => ($changeStock->stock - $int_buyvalue[$i])]);
                    if ($us) {
                        $a_stock = new StockActivity([
                            'stocks_id' => $changeStock->id,
                            'product_id' => $p_i,
                            'users_id' => $d_cashier->id,
                            'user_type_id' => 2,
                            'type_activity' => 2,
                            'stock' => $int_buyvalue[$i]
                        ]);
                        $a_stock->save();
                    }
                }
                $i++;
            }

            $faktur = new Faktur([
                'order_id' => $receipt->transaction_id,
                'faktur_number' => ($fakNum + 1),
            ]);
            $faktur->save();
            Keranjang::where('user_id', Auth::user()->id)->where('user_type', 2)->delete();
            $num_padded = sprintf("%08d", $faktur->faktur_number);
            return redirect()->route('cashier.transaction')->with('success', 'Berhasil melakukan transaksi dengan no. order #' . $receipt->transaction_id)
                ->with('id_t', $receipt->transaction_id);
            // return response()->json([
            //     'status' => 'success',
            //     'noorder' => $receipt->transaction_id,
            //     'nofaktur' => $num_padded
            // ]);
        }
    }


    public function listTransactions()
    {
        $fakturs = Faktur::orderBy('id')->get();
        $transaction = Receipts_Transaction::orderBy('id', 'DESC')->get();
        return view('cashier.reports_listTransactions', compact(['fakturs', 'transaction']));
    }

    public function previewFaktur($id)
    {
        $constCompany = DB::table('about_us')->first();
        $orderId = $id;
        $Receipt = Receipts_Transaction::where('transaction_id', $orderId)->where('order_via', 2)->first();
        // dd($Receipt);
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
        // dd($Receipt, $retur);
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
        $sales = User::all();
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
}
