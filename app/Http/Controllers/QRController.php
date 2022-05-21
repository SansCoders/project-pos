<?php

namespace App\Http\Controllers;

use App\BuktiTransfer;
use App\Faktur;
use App\Receipts_Transaction;
use Illuminate\Http\Request;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;

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
        $FakturDetails = Faktur::where('order_id', $invoice)->first();
        $ReceipTransaction = Receipts_Transaction::where('transaction_id', $invoice)->where('user_id', auth()->user()->id)->first();

        if ($FakturDetails == null || $ReceipTransaction == null) {
            return redirect()->back()->with('error', 'Faktur tidak ditemukan');
        }
        return view('detailsPembayaranQr', compact('FakturDetails'));
    }

    public function uploadPembayaran(Request $request, $invoiceNumber)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);
        $DataInvoice = Faktur::where('order_id', $invoiceNumber)->firstOrFail();
        $file = $request->file('file');
        $fileName = time() . "." . $file->getClientOriginalExtension();
        $folder = public_path('bukti_pembayaran/' . $invoiceNumber);
        $locationImg = "bukti_pembayaran/" . $invoiceNumber;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0777, true);
            $mv = $file->move($locationImg, $fileName);
            if (!$mv) {
                return redirect()->back()->with('error', 'Gagal upload bukti pembayaran');
            }
        } else {
            $mv = $file->move($locationImg, $fileName);
            if (!$mv) {
                return redirect()->back()->with('error', 'Gagal upload bukti pembayaran');
            }
        }
        $buktiTransfer = new BuktiTransfer();
        $buktiTransfer->invoices_id = $DataInvoice->id;
        $buktiTransfer->user_id = auth()->user()->id;
        $buktiTransfer->user_type = 3;
        $buktiTransfer->bank_info_id = $request->bank_id;
        $buktiTransfer->bukti_transfer_image_path = $locationImg . "/" . $fileName;
        $buktiTransfer->keterangan = "Pembayaran untuk invoice " . $invoiceNumber;
        $buktiTransfer->save();
        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload, silahkan menunggu untuk diverifikasi');
    }

    public function deleteBuktiPembayaran(Request $request)
    {
        $buktiTransfer = BuktiTransfer::find($request->id);
        $buktiTransfer->delete();
        return redirect()->back()->with('success', 'Bukti pembayaran berhasil dihapus');
    }
}
