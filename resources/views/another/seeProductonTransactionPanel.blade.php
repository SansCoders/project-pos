
<form action="{{ route('cashier.sendtocart') }}" method="POST">
    @csrf
    <div class="card-body row">
        <div class="col-lg-6 col-sm-6 mb-4">
            <img src="{{asset($the_product->img)}}" class="img-fluid rounded" alt="gambar {{$the_product->nama_product}}" />
        </div>
        <div class="col-lg-6 col-sm-6 d-flex flex-column ">
            <h2>
                {{$the_product->nama_product}}
            </h2>
            <span class="badge badge-white text-left mt-2">{{$the_product->category->name}}</span>
            <h3 class="h2 font-weight-900">@currency($the_product->price)</h3>
            <span class="my-4">
                {!! $the_product->description !!}
            </span>
            <h4 class="text-muted">Tersedia : @isset($the_product->stocks) {{ $the_product->stocks->stock }} {{ $the_product->unit->unit }}
                @else
                <b class="text-danger">habis</b> @endisset</h4>
            <div>
                <div class="form-group d-flex">
                    <input id="dataproduct" name="dataproduct" type="hidden" value="{{$the_product->id}}" required>
                    <a id="minvaltocart" href="#" style="width: 30px;height:30px;" class="btn btn-sm btn-white rounded-circle m-2">-</a>
                    <input id="valtocart" class="form-control m-2 buyval" name="valbuy" style="width: 70px;height:35px;" min="1" type="number" value="1" required>
                    <a id="addvaltocart" href="#" style="width: 30px;height:30px;" class="btn btn-sm btn-success rounded-circle m-2">+</a>                               
                </div>
                <div class="faction text-right mb-2 mt-auto">
                    <button type="submit" data-id="{{$the_product->id}}" id="add2cart" class="btn btn-success"><i class="fa fa-cart-plus"></i> tambah ke keranjang</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    var val_tc = $('#valtocart').val();
    $('.addtc').click(function(){
        add(Number($(this).data('id')),Number($('.buyval').val()));
    });
    $('#addvaltocart').on('click',function(e){
            e.preventDefault();
            val_tc = Number(val_tc) + 1;
            $('#valtocart').val(val_tc);
        });
    $('#minvaltocart').on('click',function(e){
        e.preventDefault();
        if(val_tc != 1){
            val_tc = Number(val_tc) - 1;
            $('#valtocart').val(val_tc);
        }
    });
    // function add(value,buy){
    //     var e = event || evt;
    //     var charCode = e.which || e.keyCode;                        
    //     if (charCode > 31 && (charCode < 47 || charCode > 57))
    //     return false;
    //     if (e.shiftKey) return false;
    //     arrayContains(c,value,buy);
    // }
    // function arrayContains(arr, value,buy){
    //     for(i = 0; i < arr.length; i++){
    //         if(arr[i].id == value){
    //             arr[i].buy = Number(arr[i].buy) + buy;
    //             return true;
    //         }
    //     }
    //     return c.push({'id':Number(value),'buy':Number(buy)});
    // }
    
    $('.addtc').click(function(){
        $('.xspan').click();
    })
</script>