<?php

namespace App\Http\Controllers;

use App\Receipts_Transaction;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function detailsUser($id)
    {
        $cekUser = User::where('id', $id)->first();
        if ($cekUser == null) return redirect()->back();

        $istTransaction = Receipts_Transaction::where('user_id', $cekUser->id)->where('order_via', 3);
        return view('profileUser', compact(['cekUser', 'istTransaction']));
    }
}
