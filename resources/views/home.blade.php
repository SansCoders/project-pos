@extends('dashboard-layout.master')

@section('content')
<div class="header bg-gradient-danger mt-5 pb-6">
    s
</div>
<div class="container mt-5 h-100vh">
    <div class="list-group flex-wrap flex-row align-items-center" >
        @foreach ($products as $product)
        <a href="#" class="nav-link col-lg-4 col-md-6 mb-3">
            <div class="card shadow-none m-0">
                <div class="card-body d-flex">
                    <img class="card-img mr-3" style="max-width: 50%; min-width:5%" src="{{ $product->img }}" alt="" />  
                    <div class="product-info d-flex flex-column">
                        @isset($product->category->name)
                            <span class="badge badge-info mb-2" style="place-self: flex-start">
                                {{$product->category->name}}
                            </span>
                        @else
                        &nbsp;
                        @endisset
                        <h3>
                            {{$product->nama_product}}
                        </h3>
                        <span class="text-muted h5 mb-0 mt-3">stock : @isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                            @else
                            <b class="text-danger">habis</b> @endisset
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
