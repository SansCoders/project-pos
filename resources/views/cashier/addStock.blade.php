@extends('dashboard-layout.master')

@section('content')
    <div class="container-fluid mt-5">
        <div class="search">
            <form action="">
                <input type="text" class="form-control form-control-muted col-7" placeholder="search product">
            </form>
            <div class="text-right mb-3">
                <a href="#" class="badge badge-danger badge-lg">stock habis</a>
            </div>
        </div>
        <div class="row">
            @foreach ($products as $product)
                <a href="#" class="col-sm-2 col-md-3 col-xs-2  p-0">
                    <div class="card m-3 shadow-sm p-0 m-0" style="background: rgba(155, 89, 182, 0.3)">
                        <img class="card-img-top" style="align-self: center" src="{{ asset($product->img) }}" alt="gambar {{ $product->nama_product }}">
                        <div class="card-body">
                            <h3 class="text-dark">
                                {{ $product->nama_product }}
                            </h3>
                            <h2 class="priceProduct text-gray">{{ $product->price }}</h2>
                            <h5 class="text-muted">Tersedia :
                            @isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                            @else
                                <span class="text-danger">stok habis</span>
                            @endisset
                            </h5>
                        </div>
                    </div>
                </a>    
            @endforeach
        </div>
    </div>
@endsection