
@php
    $totalharga = 0;
@endphp

<div class="d-flex align-items-center flex-column cc">
    <div class="text-center">
        <i class="ni ni-cart"></i> Cart
    </div>
    <hr class="my-3">
    <form action="{{ route('cashier.confirm.viacashier') }}" id="fcb" method="POST">
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
                    <tr>
                        <td>Nama Buyer</td>
                        <td colspan="2"><input type="text" class="form-control" name="buyer_name" id="buyername_i" required></td>
                    </tr>
                    {{-- <tr>
                        <td>Diskon</td>
                        <td colspan="2">
                            <div class="form-group">
                                <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                    <input type="checkbox" class="diskon_c" onclick="add_diskon()">
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="input_diskon" disabled>
                                </div>
                            </div>
                        </td>
                    </tr> --}}
                    <tr>
                        <td colspan="3"><button type="button" class="btn btn-block btn-neutral mm">konfirmasi</button></td>
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
        // $('#delete-item-'+id).submit();
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
        confirmButtonText: 'Ya, konfirmasi'
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
                Swal.fire(
                'Berhasil dikonfirmasi.',
                'success'
                );
                $('#fcb').submit();
            }
        })
    });
    function add_diskon()
    {
        $('#input_diskon').prop('disabled', function(i, v) { return !v; });
    }
</script>