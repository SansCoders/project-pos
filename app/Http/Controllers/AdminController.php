<?php

namespace App\Http\Controllers;

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
        return view('admin.users-sales');
    }
    public function UsersCashier()
    {
        return view('admin.users-cashier');
    }
}
