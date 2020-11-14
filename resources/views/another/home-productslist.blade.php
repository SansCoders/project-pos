
        @foreach ($products as $product)
        <a href="{{route('details.product',$product->slug)}}" class="product-item nav-link col-lg-4 col-md-6 mb-3">
            <div class="card shadow-none m-0">
                <div class="card-body d-flex" style="max-height: 150px">
                    <img class="card-img mr-3" style="max-width: 50%; min-width:5%" src="{{ asset($product->img) }}" alt="" />  
                    <div class="product-info d-flex flex-column">
                        @isset($product->category->name)
                            <span class="badge badge-info mb-2" style="place-self: flex-start">
                                {{$product->category->name}}
                            </span>
                        @else
                        &nbsp;
                        @endisset
                        <h3 class="h4">
                            {{$product->nama_product}}
                        </h3>
                        <span class="text-muted h5 mb-0 mt-auto">
                            <b>@currency($product->price)</b>
                            <br>
                            stock : @isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                            @else
                            <b class="text-danger">habis</b> @endisset
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach