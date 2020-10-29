<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $categories = CategoryProduct::all();
        $products = Product::paginate(10)->sortByDesc("created_at");
        return view('home', compact(['products', 'categories']));
    }
}
