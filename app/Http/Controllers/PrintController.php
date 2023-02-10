<?php

namespace App\Http\Controllers;

use App\Receipts_Transaction;
use Illuminate\Http\Request;
use charlieuki\ReceiptPrinter\ReceiptPrinter as ReceiptPrinter;
use Illuminate\Support\Facades\DB;

class PrintController extends Controller
{
    public function printThermal()
    {
        // Set params
        $mid = '123123456';
        $store_name = env('APP_NAME', 'Nama Toko');
        $store_address = env('STORE_LOCATION', 'Alamat Toko');
        $store_phone = '1234567890';
        $store_email = 'yourmart@email.com';
        $store_website = 'yourmart.com';
        $tax_percentage = 10;
        $transaction_id = 'TX123ABC456';
        $currency = 'Rp';

        // Set items
        $items = [
            [
                'name' => 'French Fries (tera)',
                'qty' => 2,
                'price' => 65000,
            ],
            [
                'name' => 'Roasted Milk Tea (large)',
                'qty' => 1,
                'price' => 24000,
            ],
            [
                'name' => 'Honey Lime (large)',
                'qty' => 3,
                'price' => 10000,
            ],
            [
                'name' => 'Jasmine Tea (grande)',
                'qty' => 3,
                'price' => 8000,
            ],
        ];

        // Init printer
        $printer = new ReceiptPrinter;
        $printer->init(
            config('receiptprinter.connector_type'),
            config('receiptprinter.connector_descriptor')
        );

        // Set store info
        $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

        // Set currency
        $printer->setCurrency($currency);

        // Add items
        foreach ($items as $item) {
            $printer->addItem(
                $item['name'],
                $item['qty'],
                $item['price']
            );
        }
        // Set tax
        $printer->setTax($tax_percentage);

        // Calculate total
        $printer->calculateSubTotal();
        $printer->calculateGrandTotal();

        // Set transaction ID
        $printer->setTransactionID($transaction_id);

        // Set logo
        // Uncomment the line below if $image_path is defined
        //$printer->setLogo($image_path);

        // Set QR code
        $printer->setQRcode([
            'tid' => $transaction_id,
        ]);

        // Print receipt
        $printer->printReceipt();
    }


    public function printFaktur(Request $request)
    {
        $constCompany = DB::table('about_us')->first();
        $orderId = $request->orderId;
        $Receipt = Receipts_Transaction::where('id', $orderId)->first();
        if ($Receipt == null) {
            return redirect()->back()->with('error', 'tidak valid');
        }

        $num_padded = sprintf("%02d", $Receipt->facktur->faktur_number);

        // Set params 
        $mid = $Receipt->transaction_id . $num_padded;
        $store_name = isset($constCompany->name) ? $constCompany->name : 'Nama Toko';
        $store_address = isset($constCompany->address) ? $constCompany->address : 'address Toko';
        $store_phone =  isset($constCompany->phone) ? $constCompany->phone : '1234567890';
        $store_email = 'tokomu@email.com';
        $store_website = 'website.com';
        $tax_percentage = env('TAX_PERCENTAGE', 10);
        $transaction_id = $Receipt->transaction_id . $num_padded;
        $currency = 'Rp';

        $items = [];
        $i = 0;
        foreach (json_decode($Receipt->products_list) as $item) {
            $data[$i]['product_list'] = $item;
            $i++;
        }
        $i = 0;
        foreach (json_decode($Receipt->products_prices) as $item) {
            $data[$i]['product_price'] = $item;
            $i++;
        }
        $i = 0;
        foreach (json_decode($Receipt->products_buyvalues) as $item) {
            $data[$i]['buy_values'] = $item;
            $i++;
        }

        foreach ($data as $item) {
            $items[] = [
                'name' => $item['product_list'],
                'qty' => $item['buy_values'],
                'price' => $item['product_price'],
            ];
        }

        // Init printer
        $printer = new ReceiptPrinter;
        $printer->init(
            config('receiptprinter.connector_type'),
            config('receiptprinter.connector_descriptor')
        );

        // Set store info
        $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

        // Set currency
        $printer->setCurrency($currency);

        // Add items
        foreach ($items as $item) {
            $printer->addItem(
                $item['name'],
                $item['qty'],
                $item['price']
            );
        }
        // Set tax
        $printer->setTax($tax_percentage);

        // Calculate total
        $printer->calculateSubTotal();
        $printer->calculateGrandTotal();

        // Set transaction ID
        $printer->setTransactionID($transaction_id);

        // Set logo
        // Uncomment the line below if $image_path is defined
        //$printer->setLogo($image_path);

        // Set QR code
        $printer->setQRcode([
            'tid' => $transaction_id,
        ]);

        // Print receipt
        $printer->printReceipt();
    }

    public static function printFakturByTID($orderId)
    {
        $constCompany = DB::table('about_us')->first();
        $Receipt = Receipts_Transaction::where('id', $orderId)->first();
        if ($Receipt == null) {
            return redirect()->back()->with('error', 'tidak valid');
        }

        $num_padded = sprintf("%02d", $Receipt->facktur->faktur_number);

        // Set params 
        $mid = $Receipt->transaction_id . $num_padded;
        $store_name = isset($constCompany->name) ? $constCompany->name : 'Nama Toko';
        $store_address = isset($constCompany->address) ? $constCompany->address : 'address Toko';
        $store_phone =  isset($constCompany->phone) ? $constCompany->phone : '1234567890';
        $store_email = 'tokomu@email.com';
        $store_website = 'website.com';
        $tax_percentage = env('TAX_PERCENTAGE', 10);
        $transaction_id = $Receipt->transaction_id . $num_padded;
        $currency = 'Rp';

        $items = [];
        $i = 0;
        foreach (json_decode($Receipt->products_list) as $item) {
            $data[$i]['product_list'] = $item;
            $i++;
        }
        $i = 0;
        foreach (json_decode($Receipt->products_prices) as $item) {
            $data[$i]['product_price'] = $item;
            $i++;
        }
        $i = 0;
        foreach (json_decode($Receipt->products_buyvalues) as $item) {
            $data[$i]['buy_values'] = $item;
            $i++;
        }

        foreach ($data as $item) {
            $items[] = [
                'name' => $item['product_list'],
                'qty' => $item['buy_values'],
                'price' => $item['product_price'],
            ];
        }

        // Init printer
        $printer = new ReceiptPrinter;
        $printer->init(
            config('receiptprinter.connector_type'),
            config('receiptprinter.connector_descriptor')
        );

        // set font size
        $printer->setTextSize(1, 1);

        // Set store info
        $printer->setStore($mid, $store_name, $store_address, $store_phone, $store_email, $store_website);

        // Set currency
        $printer->setCurrency($currency);

        // Add items
        foreach ($items as $item) {
            $printer->addItem(
                $item['name'],
                $item['qty'],
                $item['price']
            );
        }
        // Set tax
        $printer->setTax($tax_percentage);

        // Calculate total
        $printer->calculateSubTotal();
        $printer->calculateGrandTotal();

        // Set transaction ID
        $printer->setTransactionID($transaction_id);

        // Set logo
        // Uncomment the line below if $image_path is defined
        //$printer->setLogo($image_path);

        // Set QR code
        $printer->setQRcode([
            'tid' => $transaction_id,
        ]);

        // Print receipt
        $printer->printReceipt();
    }
}
