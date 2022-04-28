<?php

namespace App\Http\Controllers;

use App\Faktur;
use Illuminate\Http\Request;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class QRController extends Controller
{
    public function qr_test()
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data(route('pembayaran.details', 1))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath(public_path('assets/img/brand/favicon.png'))
            ->labelText('silahkan scan QR code ini')
            ->labelFont(new NotoSans(20))
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();
        $result->saveToFile(public_path('qr_code.png'));
        dd($result);
    }

    public function generate_qr(Request $request)
    {
        if ($request->type == "bank") {
            $url = "#";
            $nameQR = "qr_code_bank_" . $request->bank_id;
            $pathQR = public_path('qrcode/qr_code_bank/' . $nameQR . '.png');
        } else if ($request->type == "pembayaran") {
            $url = route('pembayaran.details', $request->id);
            $nameQR = "transaction-" . $request->id;
            $pathQR = public_path('qrcode/transactions-qr/' . $nameQR . '.png');
        } else {
            return redirect()->back();
        }

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
        dd($result);
    }

    public function pembayaranDetails($invoice)
    {
        $FakturDetails = Faktur::where('order_id', $invoice)->firstOrFail();
        // dd($FakturDetails);



        return view('detailsPembayaranQr', compact('FakturDetails'));
    }
}
