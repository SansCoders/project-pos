
@php
    $totalharga = 0;
@endphp

<div class="d-flex align-items-center flex-column cc">
    <div class="text-center">
     <i class="ni ni-cart"></i> Cart
    </div>
    <hr class="my-3">
    <div class="table-responsive tblcart">
        <table class="table table-borderless table-hover">
            <form action="{{ route('cashier.confirm.viacashier') }}" id="cartCashierList" method="POST">
                @csrf
                <input type="hidden" value="2" name="buy_via" required>
                <thead>
                    <tr>
                        <th>barang</th>
                        <th>qty</th>
                        <th>harga</th>
                    </tr>
                </thead>
                <tbody> 
                @for ($i = 0; $i < count($data); $i++)
                    <tr>
                        @php
                            $idp = $data[$i]['id'];
                            $bv = $data[$i]['buy'];
                            $product = App\Product::where('id',$idp)->first();
                            $totalharga += $product->price * $bv;
                            $c_data['in_products'][$i] = (int)$idp;
                            $c_data['buy_value'][$i] = (int)$bv;
                        @endphp
                        <td>{{$product->nama_product}}</td>
                        <td>{{ $bv }} {{ $product->unit->unit }}</td>
                        <td class="d-flex">@currency($product->price * $bv) <a href="#" class="ml-auto"><i class="fa fa-minus-square text-danger"></i></a> </td>
                    </tr>
                @endfor
                    <tr class="bg-translucent-light">
                        <td colspan="2"><strong>Total</strong></td>
                        <td>@currency($totalharga)</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>Nama Buyer</td>
                        <td colspan="2"><input type="text" class="form-control" name="buyer_name" id="buyername_i" required></td>
                    </tr>
                    <tr>
                        <td colspan="3"><button type="submit" class="btn btn-block btn-neutral mm">konfirmasi</button></td>
                    </tr>
                </tfoot>
            </form>
        </table>
    </div>

</div>

<script>
    var product_w_buyvalue = [];
    $('.mm').click(function(){
        var data = @json($c_data);
        var buyer_name = $('#buyername_i').val();
        $.ajax({
            url : "/cashier/t/confirm",
            method:"post",
            data : {_token:"{{ csrf_token() }}",buyer_name : buyer_name, data : data},
            success: function(resp){
                // window.location.href = "/home";
                data = null;
                buyer_name = null;
                $('.cc').html('order no <b>'+resp.noorder+'</b><br>BERHASIL');
                console.log(resp.status);
            }
        });
    });
</script>