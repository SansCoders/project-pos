<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('admin.home');
    }

    public function UsersSales()
    {
        $sales = User::all();
        return view('admin.users-sales', compact('sales'));
    }

    public function UsersCashier()
    {
        return view('admin.users-cashier');
    }

    public function storeUserSales(Request $request)
    {
        dd($request);
    }
}
