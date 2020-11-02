<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Keranjang;
use App\Product;
use App\Receipts_Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::all();
        $products = Product::all()->sortByDesc("created_at");
        return view('home', compact(['products', 'categories', 'cart', 'cekTransactions']));
    }
}
