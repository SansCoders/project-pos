@foreach($Cart as $item)
<div class="d-flex align-items-center my-2">
    <div class=""><button type="button" class="btn btn-sm btn-icon btn-outline-danger border-0 delete-item" data-cartid="{{$item->id}}"><i class="fa fa-trash"></i></button></div>
    <div class="">{{$item->nama_product}}</div>
    <div class="ml-auto">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary btn-sm min-qty" data-cartid="{{$item->id}}">-</button>
            <input type="number" style="max-width: 50px;" min="1" class="form-control cvqty" data-cartid="{{$item->id}}" value="{{$item->buy_value}}" name="" id="">
            <button type="button" class="btn btn-secondary btn-sm plus-qty" data-cartid="{{$item->id}}">+</button>
        </div>
    </div>
    <div class="ml-2 pl-2 tothrg-{{$item->id}}">
        @currency($item->total_harga)
    </div>
</div>
@endforeach