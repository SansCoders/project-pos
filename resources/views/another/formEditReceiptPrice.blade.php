<div class="text-center">
    <div class="card shadow-sm">
        <img class="card-img" src="{{ asset($checkProduct->img) }}" style="max-width: 200px;place-self: center;" alt="">
        <div class="card-body">
            {{$checkProduct->nama_product}}
            <br>
            Total Harga Normal <strong>@currency($previousTotalPrice)</strong>
        </div>
    </div>
    <form action="{{ route('cashier.editPriceReceipt.put',$transaction->id)}}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <div class="input-group">
            <input type="hidden" name="idproduct" value="{{ $product_id }}">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">Price</span>
                </div>
                <input class="form-control text-right" type="number" value="{{$previousTotalPrice}}" min="1" name="priceTotalCustom">
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-block">update</button>
        </div>
    </form>
</div>