<?php

namespace App\Http\Controllers;

use App\Cashier;
use App\Product;
use App\Receipts_Transaction;
use App\Stock;
use Carbon\Carbon;
use App\Faktur;
use Illuminate\Http\Request;
use App\StockActivity;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $stockCount = Stock::where('stock', 0)->get();
        $transactionPending = Receipts_Transaction::where('is_done', 0)->get();
        return view('cashier.home', compact(['transactionPending', 'stockCount']));
    }
    public function transactionProduct()
    {
        $transactions = Receipts_Transaction::orderBy('created_at')->get();
        return view('cashier.transaction', compact('transactions'));
    }
    public function newTransaction()
    {
        $products = Product::all()->sortByDesc("created_at");
        return view('cashier.new-transaction', compact('products'));
    }
    public function getdatalistCartContent(Request $request)
    {
        if ($request->data == null) return "cart kosong";
        $data = $request->data;
        return view('another.cartLoadCashier', compact('data'));
    }
    public function getdataSeeProduct(Request $request)
    {
        $the_product = Product::where('id', $request->idp)->first();
        if ($the_product == null) return "not valid";
        if ($request->c != null) {
            $c = $request->c;
        } else {
            $c = [];
        }
        return view('another.seeProductonTransactionPanel', compact(['the_product', 'c']));
    }
    public function processCheckout($orderid)
    {
        $transaction = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->first();
        if ($transaction == null) {
            return abort(404);
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
        $receipt = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->first();
        if ($receipt == null) {
            return redirect()->back();
        }

        $confirm = Receipts_Transaction::where('transaction_id', $orderid)->where('is_done', 0)->update([
            'cashier_id' => $d_cashier->id,
            'cashier_name' => $d_cashier->name,
            'is_done' => 1,
            'done_time' => date("Y-m-d h:i:s", time())
        ]);

        if (!$confirm) {
            return redirect()->back()->with('error', 'gagal konfirmasi, cek kembali datanya!');
        }

        return redirect()->route('cashier.transaction')->with('success', 'berhasil diproses');
    }
    public function confirmCheckoutviaCashier(Request $request)
    {
        $d_cashier = Auth::user();

        // $request->validate([
        //     'productsId[]' => 'required|numeric',
        //     'buyvalue[]' => 'required|numeric',
        //     'buyer_name' => 'required|min:3'
        // ]);

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
            'order_via' => 2
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
            $num_padded = sprintf("%08d", $faktur->faktur_number);
            return redirect()->route('cashier.transaction')->with('success', 'Berhasil melakukan transaksi dengan no. order #' . $receipt->transaction_id);
            // return response()->json([
            //     'status' => 'success',
            //     'noorder' => $receipt->transaction_id,
            //     'nofaktur' => $num_padded
            // ]);
        }
    }
}
