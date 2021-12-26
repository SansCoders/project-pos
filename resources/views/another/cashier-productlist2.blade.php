@foreach ($products as $item)
    @if ($item->stocks->stock > 0 && $item->product_status == "show")
        <div class="col-lg-4 col-md-6">
            <div data-idp="{{$item->id}}" class="seeProduct" data-toggle="modal">
                <div class="card shadow-none shadow-lg--hover">
                    <div class="card-body d-flex" style="max-height: 200px">
                        <img class="card-img mr-3" src="{{asset($item->img)}}" alt="" style="max-width: 50%; min-width:5%">
                        <div class="ket ml-3 d-flex flex-column">
                            <strong class="text-dark">{{ $item->nama_product }}</strong>
                            <span class="text-dark mb-3">Stocks : {{ $item->stocks->stock }}&nbsp;{{ $item->unit->unit }}</span>
                            
                            <button class="btn btn-success btn-sm mt-auto">+ Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach