
@foreach ($products as $product)
        @if ($product->product_status == "show") 
            @php
                $custom_price = DB::table('prices__customs')->where('product_id',$product->id)->where('user_id',Auth::user()->id)->where('user_type','user')->first();
            @endphp
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
                                @if ($custom_price != null) 
                                    <b>@currency($custom_price->prices_c)</b>
                                @else
                                    <b>@currency($product->price)</b>
                                @endif
                                <br>
                                stock : @isset($product->stocks) 
                                @if ($product->stocks->stock < 1)
                                <b class="text-danger">habis</b>
                                @else
                                {{ $product->stocks->stock }} {{ $product->unit->unit }}
                                @endif
                                @else
                                <b class="text-danger">habis</b> @endisset
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endif
@endforeach