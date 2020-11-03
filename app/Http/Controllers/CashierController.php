<?php

namespace App\Http\Controllers;

use App\Cashier;
use App\Product;
use App\Receipts_Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $transactionPending = Receipts_Transaction::where('is_done', 0)->get();
        return view('cashier.home', compact('transactionPending'));
    }
    public function transactionProduct()
    {
        $transactions = Receipts_Transaction::orderBy('created_at')->get();
        return view('cashier.transaction', compact('transactions'));
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
}
