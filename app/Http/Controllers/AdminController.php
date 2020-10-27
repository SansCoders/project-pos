<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $sales = User::paginate(10);
        return view('admin.users-sales', compact('sales'));
    }

    public function UsersCashier()
    {
        return view('admin.users-cashier');
    }

    public function storeUserSales(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'username' => 'required|min:3',
            'password' => 'required|min:6',
        ]);
        $encpass = Hash::make($request->password, [
            'rounds' => 12,
        ]);
        $newUser = new User([
            'name' => $request->name,
            'username' => $request->username,
            'password' => $encpass,
            'remember_token' => $request->_token
        ]);
        $newUser->save();
        return redirect()->back()->with('success', "success added user");
    }
}
