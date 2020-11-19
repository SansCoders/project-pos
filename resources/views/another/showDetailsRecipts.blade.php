@php
    $data = [];
    $i = 0;
    $totHarga = 0;
    $totHargas = 0;
    foreach (json_decode($dataReceipt->products_id) as $item){
        $getDataProduct = App\Product::where('id',$item)->first();
        $data[$i]['product_satuan'] = $getDataProduct->price;
        $data[$i]['product_unit'] = $getDataProduct->unit->unit;
        $i++;
    }
    $i = 0;
    foreach (json_decode($dataReceipt->products_list) as $item){
        $data[$i]['product_list'] = $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($dataReceipt->products_prices) as $item){
        $data[$i]['product_prices'] = $item;
        $totHarga += $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($dataReceipt->total_productsprices) as $item){
        $data[$i]['total_productsprices'] = $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($dataReceipt->products_buyvalues) as $item){
        $data[$i]['buy_values'] = $item;
        $i++;
    }
    $i = 0;
    if($dataReceipt->custom_prices != null)
    {
        foreach (json_decode($dataReceipt->custom_prices) as $item){
            $data[$i]['custom_prices'] = $item;
            $i++;
        }
    }else{
        foreach (json_decode($dataReceipt->products_prices) as $item){
            $data[$i]['custom_prices'] = $item;
            $i++;
        }
    }
    $i = 0;
@endphp

<div id="loading">
    <i id="loading-icon" class="fas fa-spinner fa-spin"></i>
</div>
<div class="contentDetails">
    <div class="table-responsive">
        <table class="mb-3">
            <tr>
                <td>Order Id</td>
                <td width="20px">:</td>
                <td><strong>#{{$dataReceipt->transaction_id}}</strong></td>
            </tr>
            <tr>
                <td>Waktu Order</td>
                <td>:</td>
                <td>{{strftime('%H:%M ,%d %B %Y',strtotime($dataReceipt->created_at))}}</td>
            </tr>
            <tr>
                <td>Selesai Diproses</td>
                <td>:</td>
                <td>
                    @isset($dataReceipt->done_time)
                    {{strftime('%H:%M ,%d %B %Y',strtotime($dataReceipt->done_time))}}
                    @endisset
                </td>
            </tr>
            <tr>
                <td>Customer</td>
                <td width="20px">:</td>
                <td>
                    @isset($dataReceipt->user_name)
                        <strong>{{$dataReceipt->user_name}}</strong>
                    @endisset
                </td>
            </tr>
            <tr>
                <td>Kasir</td>
                <td width="20px">:</td>
                <td>
                    @isset($dataReceipt->cashier_name)
                        <strong>{{$dataReceipt->cashier_name}}</strong>
                    @endisset
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><strong>
                        @if ($dataReceipt->status == 'pending')
                           <span class="text-warning">Pending</span>
                        @elseif ($dataReceipt->status == 'confirmed')   
                            <span class="text-success">Selesai</span>
                        @elseif ($dataReceipt->status == 'canceled')   
                            Canceled
                        @else
                            -
                        @endif
                    </strong>
                </td>
            </tr>
        </table>
        <table class="table table-flush table-borderless table-hover">
            <thead>
                <tr>
                    <th>keterangan</th>
                    <th>qty</th>
                    <th>harga satuan</th>
                    <th>total harga</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $item)
                    <tr style="border-bottom: 0px">
                        <td>{{$item['product_list']}}</td>
                        <td>{{$item['buy_values']}} {{$item['product_unit']}}</td>
                        <td>@currency($item['product_satuan'])</td>
                        @if ($item['custom_prices'] != $item['product_prices'])
                        <td>@currency($item['custom_prices']*$item['buy_values'])</td>
                        @else
                        <td>@currency($item['product_prices']*$item['buy_values'])</td>
                        @endif

                        
                        @if ($dataReceipt->custom_prices != null)
                            @php
                                if ($item['custom_prices'] != $item['product_satuan']){
                                    $totHargas += $item['custom_prices'] * $item['buy_values'];
                                }else{
                                    $totHargas += $item['product_prices'] * $item['buy_values'];
                                }
                                $i++;
                            @endphp
                        @else
                            @php
                                $totHargas += $item['product_prices'] * $item['buy_values'];
                                $i++;
                            @endphp     
                        @endif
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="3" class="text-center">Total Harga Keseluruhan</td>
                    <td>@currency($totHargas)</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


    
<script>
    $("#detailsProduct").on('shown.bs.modal', function () {
        if ($('.contentDetails').length > 0) {
            setTimeout(removeLoader, 100);

        }
    });
        function removeLoader(){
            $( "#loading" ).fadeOut(100, function() {
            $( "#loading" ).remove();
            });  
        }
  </script>