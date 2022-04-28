<?php

namespace App\Http\Controllers;

use App\BankInfo;
use Illuminate\Http\Request;

class BankInfoController extends Controller
{
    public function addBankInfo(Request $request)
    {
        // dd($request);
        $bankInfo = new BankInfo();
        $bankInfo->bank_name = $request->bank_name;
        $bankInfo->rekening_number = $request->rekening_number;
        $bankInfo->rekening_owner_name = $request->owner_acc;
        $bankInfo->qr_code = "#";
        $bankInfo->save();
    }
}
