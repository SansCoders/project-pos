@php
    setlocale(LC_TIME, 'id');
    $data = [];
    $i = 0;
    $totHarga = 0;
    $totHargas = 0;
    foreach (json_decode($Receipt->products_id) as $item){
        $getDataProduct = App\Product::where('id',$item)->first();
        $data[$i]['product_satuan'] = $getDataProduct->price;
        $data[$i]['product_unit'] = $getDataProduct->unit->unit;
        $i++;
    }
    $i = 0;
    foreach (json_decode($Receipt->products_list) as $item){
        $data[$i]['product_list'] = $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($Receipt->products_prices) as $item){
        $data[$i]['product_prices'] = $item;
        $totHarga += $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($Receipt->total_productsprices) as $item){
        $data[$i]['total_productsprices'] = $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($Receipt->products_buyvalues) as $item){
        $data[$i]['buy_values'] = $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($Receipt->custom_prices) as $item){
        $data[$i]['custom_prices'] = $item;
        $i++;
    }
    $x = sprintf("%02d", $Receipt->facktur->faktur_number);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice-{{ $Receipt->transaction_id }}{{ $x }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" >
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <style>
        .page-break {
            page-break-after: always;
        }
        </style>
</head>
<body>
    @isset($preview)
        <div class="container">
    @endisset
        <div class="text-center" style="margin-bottom: 2rem">
            <h3 class="font-weight-bolder">FAKTUR PENJUALAN</h3>
        </div>
        <div style="margin-bottom: 4rem">
            <div class="text-left float-right" style="max-width: 350px">
                <span class="h6">
                    @if ($Receipt->done_time != null)
                        {{strftime('%H:%M ,%d %B %Y',strtotime($Receipt->done_time))}}
                    @endif
                </span><br />
                <span class="font-weight-bolder">{{$constCompany->name}}</span><br />
                <p style="font-size: 12px; max-width: 150px">{{$constCompany->address}}</p>
                <span class="font-weight-bolder" style="font-size: 12px">{{$constCompany->phone}}</span>
            </div>
            <table class="mt-4">
                <tr>
                    <th width="130px">No. Faktur</th>
                    <th width="30px">:</th>
                    <td>
                        @php
                            $num_padded = sprintf("%08d", $Receipt->facktur->faktur_number);
                        @endphp
                        {{$num_padded}}
                    </td>
                </tr>
                <tr>
                    <th>No. Order</th>
                    <th>:</th>
                    <td>#{{$Receipt->transaction_id}}</td>
                </tr>
                <tr>
                    <th>Pembeli</th>
                    <th>:</th>
                    <td>{{$Receipt->user_name}}</td>
                </tr>
                <tr>
                    <th>Kasir</th>
                    <th>:</th>
                    <td>{{$Receipt->cashier_name}}</td>
                </tr>
                <tr>
                    <th>Tanggal Order</th>
                    <th>:</th>
                    <td>{{strftime('%H:%M ,%d %B %Y',strtotime($Receipt->created_at))}}</td>
                </tr>
                <tr>
                    <th>Selesai Diproses</th>
                    <th>:</th>
                    @if ($Receipt->done_time == null)
                    <td>-</td>
                    @else    
                    <td>{{strftime('%H:%M ,%d %B %Y',strtotime($Receipt->done_time))}}</td>
                    @endif
                </tr>
                <tr>
                    <th>Status</th>
                    <th>:</th>
                    <td>
                        @if ($Receipt->status == 'pending')
                            Pending
                        @elseif ($Receipt->status == 'confirmed')
                            Selesai
                        @elseif ($Receipt->status == 'canceled')
                            Canceled
                        @else    
                            -
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <table class="table table-borderless" style="border:2px solid black">
            <thead>
                <tr style="border-bottom: 2px solid">
                    <th width="5%" class="text-center">No.</th>
                    <th width="50%" class="text-center">Keterangan</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="15%" class="text-center">Satuan</th>
                    <th width="20%" class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 0;
                @endphp
                @foreach ($data as $item)
                    <tr style="border-bottom: 0px">
                        <td style="border-right: 1px solid">{{ $i+1}}</td>
                        <td style="border-right: 1px solid">{{$item['product_list']}}</td>
                        <td style="border-right: 1px solid" class=" text-center">{{$item['buy_values']}} {{$item['product_unit']}}</td>
                        @if ($item['custom_prices'] != $item['product_satuan'])
                            <td style="border-right: 1px solid" class="text-center">@currency($item['custom_prices'])</td>
                            <td style="border-right: 1px solid" class=" text-center" >@currency($item['custom_prices']*$item['buy_values'])</td>
                        @else    
                            <td style="border-right: 1px solid" class="text-center">@currency($item['product_satuan'])</td>
                            <td style="border-right: 1px solid" class=" text-center" >@currency($item['product_prices']*$item['buy_values'])</td>
                        @endif
                    </tr>
                    @php
                        if ($item['custom_prices'] != $item['product_satuan']){
                            $totHargas += $item['custom_prices'] * $item['buy_values'];
                        }else{
                            $totHargas += $item['product_prices'] * $item['buy_values'];
                        }
                        $i++;
                    @endphp
                @endforeach
                <tr>
                    <td style="border-top: 1px solid" colspan="4"><b>Total</b></td>
                    <td style="border-top: 1px solid" class="text-center" >@currency($totHargas)</td>
                </tr>
            </tbody>
        </table>

        <div class="margin-top: 60px;">
            &nbsp;
            <div class="text-right w-100 mb-4">
                Cikarang Utara, {{ \Carbon\Carbon::now()->formatLocalized('%d %B %Y') }}
            </div>
        </div>
        <div style="float: left">
            <p style="margin-left: 10px">Customer</p>
            <div style="margin-top: 5rem">
                (<i>{{$Receipt->user_name}}</i>)
            </div>
        </div>
        <div style="float: right">
            <p style="margin-left: 12px">Cashier</p>
            <div style="margin-top: 5rem">
                @if ($Receipt->cashier_name == null)
                (<i>-</i>)
                @else    
                (<i>{{$Receipt->cashier_name}}</i>)
                @endif
            </div>
        </div>

    @isset($preview)
        </div>
    @endisset
        <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>