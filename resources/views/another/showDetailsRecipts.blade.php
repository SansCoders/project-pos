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
        $totHargas += $item;
        $i++;
    }
    $i = 0;
    foreach (json_decode($dataReceipt->products_buyvalues) as $item){
        $data[$i]['buy_values'] = $item;
        $i++;
    }
    $i = 0;
@endphp

<div id="loading">
    <i id="loading-icon" class="fas fa-spinner fa-spin"></i>
</div>
<div class="contentDetails">
    Order Id : <strong>#{{$dataReceipt->transaction_id}}</strong><br>
    Waktu Order : <strong>{{strftime('%H:%M ,%d %B %Y',strtotime($dataReceipt->created_at))}}</strong>
    Status :
    @if ($dataReceipt->is_done == 0)
    Pending
    @else    
    Selesai
    @endif
    <div class="table-responsive">
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
                        <td>@currency($item['product_prices']*$item['buy_values'])</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                    @endforeach
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