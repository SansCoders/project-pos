<?php

namespace App\Http\Controllers;

use App\BankInfo;
use Illuminate\Http\Request;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class BankInfoController extends Controller
{
    public function detailsBankInfo($id)
    {
        $BankInfo = BankInfo::find($id);
        return $BankInfo->bank_name;
    }

    public function addBankInfo(Request $request)
    {
        // dd($request);
        $bankInfo = new BankInfo();
        $bankInfo->bank_name = $request->bank_name;
        $bankInfo->rekening_number = $request->rekening_number;
        $bankInfo->rekening_owner_name = $request->owner_acc;
        if ($bankInfo->save()) {
            $url = route('details-bank-info', $bankInfo->id);
            $nameQR = "qr_code_bank_" . $bankInfo->id;
            $pathQR = public_path('qrcode/qr_code_bank/' . $nameQR . '.png');

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                // ->logoPath(public_path('assets/img/brand/favicon.png'))
                ->labelText('silahkan scan QR code ini')
                ->labelFont(new NotoSans(20))
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();
            $result->saveToFile($pathQR);
            $bankInfo->qr_code = 'qrcode/qr_code_bank/' . $nameQR . '.png';
            $bankInfo->save();
            return redirect()->route('details-bank-info', $bankInfo->id)->with('success', 'Bank Info berhasil ditambahkan');
        } else {
            return redirect()->back();
        }
    }

    public function deleteBank(Request $request)
    {
        $BankInfo = BankInfo::find($request->id);
        if ($BankInfo != null) {
            $BankInfo->delete();
            return redirect()->back()->with('success', 'Info Bank berhasil dihapus');
        }
        return redirect()->back()->with('error', 'Info Bank tidak ditemukan');
    }
}
