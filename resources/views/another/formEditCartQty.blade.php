<div class="text-center">
    <div class="card shadow-sm">
        <img class="card-img" src="{{ asset($checkProduct->img) }}" style="max-width: 200px" alt="">
        <div class="card-body">
            {{$checkProduct->nama_product}}
        tersedia <strong>{{ $checkProduct->stocks->stock }}{{ $checkProduct->unit->unit }}</strong>
        </div>
    </div>
    <form action="{{ route('editQtyCart.put',$cart->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Qty</span>
                </div>
                <input class="form-control" type="number" value="{{$cart->buy_value}}" min="1" name="buy_value">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">update</button>
        </div>
    </form>
</div>