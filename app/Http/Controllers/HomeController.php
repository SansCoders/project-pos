<?php

namespace App\Http\Controllers;

use App\CategoryProduct;
use App\Keranjang;
use App\Product;
use App\Receipts_Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $constCompany = DB::table('about_us')->first();
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::all();
        $products = Product::all()->sortByDesc("created_at");
        return view('home', compact(['products', 'categories', 'cart', 'cekTransactions', 'constCompany']));
    }

    public function getProductbyCategorybyName($name)
    {
        $constCompany = DB::table('about_us')->first();
        $cekTransactions = Receipts_Transaction::where('user_id', Auth::user()->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        $categories = CategoryProduct::where('name', $name)->first();
        if ($categories == null) {
            return abort(404);
        }
        $products = Product::where('category_id', $categories->id)->get();
        return view('home', compact(['products', 'categories', 'cart', 'cekTransactions', 'constCompany']));
    }

    public function myProfile()
    {
        $user = Auth::user();
        $cekTransactions = Receipts_Transaction::where('user_id', $user->id)->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', $user->id)->get();

        return view('myprofile', compact(['user', 'cart', 'cekTransactions']));
    }

    public function myOrders()
    {
        $receipts = Receipts_Transaction::where('user_id', Auth::user()->id);
        $allOrders = $receipts->orderBy('is_done', 'ASC')->paginate(10);
        $cekTransactions = $receipts->where('is_done', 0)->orderBy('created_at', 'DESC')->get();
        $cart = Keranjang::where('user_id', Auth::user()->id)->get();
        return view('ordersList', compact(['cart', 'allOrders', 'cekTransactions']));
    }

    public function getdataReceipts(Request $request)
    {

        $idReceipt = $request->idReceipts;
        $getData = Receipts_Transaction::where('id', $idReceipt)->first();
        if (!$getData || $getData == null) {
            $data = "kosong";
        }
        $data = json_encode($getData);
        return response()->json($data);
    }

    public function cetakFaktur(Request $request)
    {
        // $pdf = PDF::loadView('invoice');
        // return $pdf->download('invoice.pdf');
        // return view('invoice');
        $constCompany = DB::table('about_us')->first();
        $user = Auth::user();
        $orderId = $request->orderId;
        $Receipt = Receipts_Transaction::where('id', $orderId)->where('user_id', $user->id)->first();
        if ($Receipt == null) {
            return redirect()->back()->with('error', 'tidak valid');
        }
        $pdf = PDF::loadView('invoice', compact(['Receipt', 'constCompany']));
        $num_padded = sprintf("%02d", $Receipt->facktur->faktur_number);
        $filename = "invoice-" . $Receipt->transaction_id . $num_padded . ".pdf";
        //return $pdf->download($filename);
        return view('invoice', compact(['Receipt', 'constCompany']));
        // dd($orderId);
    }
}
