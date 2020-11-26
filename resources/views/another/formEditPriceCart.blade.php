<div class="text-center">
    <div class="card shadow-sm">
        <img class="card-img" src="{{ asset($checkProduct->img) }}" style="max-width: 200px;place-self: center;" alt="">
        <div class="card-body">
            {{$checkProduct->nama_product}}
            <br>
            Harga <strong>@currency($checkProduct->price * $cart->buy_value)</strong> untuk {{$cart->buy_value}} {{ $checkProduct->unit->unit }}
        </div>
    </div>
    <form action="{{ route('cashier.editPriceCart.put',$cart->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Price</span>
                </div>
                <input class="form-control text-right" type="number" value="{{$cart->custom_price}}" min="1" name="priceCustom">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">update</button>
        </div>
    </form>
</div>