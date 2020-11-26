
@php
    $totalharga = 0;
@endphp

<div class="d-flex align-items-center flex-column cc">
    <div class="text-center">
        <i class="ni ni-cart"></i> Cart
    </div>
    <hr class="my-3">
    <form action="{{ route('cashier.confirm.viacashier') }}" id="fcb" method="POST" style="width: 100%">
        @csrf
        <table class="table table-borderless table-hover">
                <thead>
                    <tr>
                        <th>barang</th>
                        <th>qty</th>
                        <th>harga</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($data as $item)
                    @php
                        $product = App\Product::where('id',$item->product_id)->first();
                        $totalharga += $product->price * $item->buy_value ;
                    @endphp
                    <input type="number" name="productsId[]" value="{{ $product->id }}" hidden>
                    <input type="number" name="buyvalue[]" value="{{  $item->buy_value }}" hidden>
                    <tr id="p-{{$item->id}}">
                        <td>{{$product->nama_product}}</td>
                        <td>{{ $item->buy_value }} {{ $product->unit->unit }}</td>
                        <td class="d-flex">@currency($product->price * $item->buy_value) <a href="#" data-itm="{{$item->id}}" class="ml-auto rmitem"><i class="fa fa-minus-square text-danger"></i></a> </td>
                    </tr>
                    @endforeach
                    <tr class="bg-translucent-light">
                        <td colspan="2"><strong>Total</strong></td>
                        <td>@currency($totalharga)</td>
                    </tr>
                </tbody>
                <tfoot>
                    {{-- <tr>
                        <td>Nama Buyer</td>
                        <td colspan="2"><input type="text" class="form-control" name="buyer_name" id="buyername_i" required></td>
                    </tr> --}}
                    <tr>
                        <td colspan="3"><a href="{{route('cashier.myCheckout')}}" class="btn btn-block btn-neutral">Checkout</a></td>
                    </tr>
                </tfoot>
        </table>
    </form>
</div>

<script>
    $('.rmitem').click(function(e){
        e.preventDefault();
        var id = $(this).data('itm');
        $('#p-'+id).remove();
        $.ajax({
            url : "/cashier/cart/delete-"+id,
            method:"post",
            data : {"_token":"{{ csrf_token() }}",'id' : id},
            success: function(resp){
                console.log(resp);
            }
        });
    });
    $('.mm').click(function(){ 
        if($('#buyername_i').val().length < 3)
        {
            return Swal.fire(
            'Data tidak valid!',
            'Nama Buyer harus ada dan minimal 3 huruf',
            'error'
            );
        }
        Swal.fire({
        title: 'Konfirmasi Pembelian ?',
        text: "Pastikan data sudah benar dan sesuai",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, konfirmasi',
        preConfirm: () => {
            Swal.showLoading()
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(true)
                }, 1000)
            })
        }
        }).then((result) => {
            if (result.isConfirmed) {
                if($('#buyername_i').val().length < 3)
                {
                    return Swal.fire(
                    'Data tidak valid!',
                    'Nama Buyer harus ada dan minimal 3 huruf',
                    'error'
                    );
                }
                var form =  $('#fcb');
                var varFaktur = null;
                $.ajax({
                    url : form.attr('action'),
                    method:"post",
                    data : form.serialize(),
                    success: function(resp){
                        if(resp.status == "error"){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: resp.msg
                            });
                        }else if(resp.status == "success"){
                            var varFaktur = resp.noorder;
                            Swal.fire({
                                icon: 'success',
                                title: resp.msg,
                                html : '<a href="/invoice/view-'+varFaktur+'" target="_blank" class="btn btn-success text-white">Print Faktur</a>'
                            });
                            $.ajax({
                                url : "/cashier/clt",
                                method:"post",
                                data : {_token:"{{ csrf_token() }}"},
                                success: function(resp){
                                    $('.content-popup').html(resp);
                                }
                            });
                            window.open("/invoice/view-"+varFaktur);
                        }
                    }
                });
            }
        })
    });
    function add_diskon()
    {
        $('#input_diskon').prop('disabled', function(i, v) { return !v; });
    }
</script>