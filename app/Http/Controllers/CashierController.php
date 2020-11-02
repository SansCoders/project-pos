<?php

namespace App\Http\Controllers;

use App\Receipts_Transaction;
use Illuminate\Http\Request;

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
}
