@extends('dashboard-layout.master')

@section('content')
<nav class="header bg-gradient-gray">
    <div class="container">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                        <div class="form-group mb-0">
                          <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" placeholder="Cari Produk" type="text">
                          </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                      </form>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('checkout')}}" role="button" class="btn btn-icon btn-primary">
                        <span class="btn-inner--icon"><i class="fa fa-cash-register"></i></span>
                        @if ($cart->count() > 0)
                        <span class="btn-inner--text">Checkout ({{$cart->count()}}) </span>
                        @else
                        <span class="btn-inner--text">Checkout</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5 h-100vh">
    @if ($cekTransactions->count() > 0)
        <div class="row">
            <div class="col-lg-12">
                <a href="#">
                    <div class="alert alert-default d-flex align-items-center" role="alert">
                        <div class="alert-desc">
                            <strong>1 Transaksi</strong> menunggu diproses 
                        </div>
                        <span class="ml-auto">Lihat</span>
                    </div>
                </a>
            </div>
        </div>
    @endif
    <div class="list-group flex-wrap flex-row align-items-center" >
        @foreach ($products as $product)
        <a href="{{route('details.product',$product->slug)}}" class="nav-link col-lg-4 col-md-6 mb-3">
            <div class="card shadow-none m-0">
                <div class="card-body d-flex" style="max-height: 150px">
                    <img class="card-img mr-3" style="max-width: 50%; min-width:5%" src="{{ $product->img }}" alt="" />  
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
    </div>
</div>
@endsection
