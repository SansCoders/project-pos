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
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::all();
        $products = Product::all()->sortByDesc("created_at");
        return view('home', compact(['products', 'categories', 'cart', 'cekTransactions']));
    }

    public function getProductbyCategorybyName($name)
    {
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::where('name', $name)->first();
        if ($categories == null) {
            return abort(404);
        }
        $products = Product::where('category_id', $categories->id)->get();
        return view('home', compact(['products', 'categories', 'cart', 'cekTransactions']));
    }
}