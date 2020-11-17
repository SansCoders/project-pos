@extends('dashboard-layout.master')
@include('dashboard-layout.footer')
@section('add-css')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }
    </style>
@endsection
@section('content')

@foreach($data as $the_product)
    @php
        $custom_price = DB::table('prices__customs')->where('product_id',$the_product->id)->where('user_id',Auth::user()->id)->where('user_type','user')->first();
    @endphp
    <nav class="header mb-4">
        <div class="container">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        {{-- <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-dark mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('categ.name',$the_product->category->name) }}">{{$the_product->category->name}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$the_product->nama_product}}</li>
                            </ol>
                        </nav> --}}
                        <a href="{{ route('home') }}" class="btn btn-light"><i class="fa fa-arrow-alt-circle-left"></i></a>
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
        <div class="container">
            <div class="mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-dark mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('categ.name',$the_product->category->name) }}">{{$the_product->category->name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$the_product->nama_product}}</li>
                    </ol>
                </nav>
            </div>
            <div class="card shadow-none">
                <div class="card-body row">
                    <div class="col-lg-6 col-sm-6 mb-4">
                        <img src="{{asset($the_product->img)}}" class="img-fluid rounded" alt="gambar {{$the_product->nama_product}}" />
                    </div>
                    <div class="col-lg-6 col-sm-6 d-flex flex-column ">
                        <h2>
                            {{$the_product->nama_product}}
                        </h2>
                        <span class="badge badge-white text-left mt-2">{{$the_product->category->name}}</span>
                        <h3 class="h2 font-weight-900">
                            @if ($custom_price != null) 
                                <s>@currency($the_product->price)</s> <span class="text-danger">@currency($custom_price->prices_c)</span>
                                <span class="badge badge-info">Harga Khusus</span>
                            @else
                                @currency($the_product->price)
                            @endif
                        </h3>
                        <span class="my-4">
                            {!! $the_product->description !!}
                        </span>
                        <h4 class="text-muted">Tersedia : @isset($the_product->stocks)
                            @if ($the_product->stocks->stock < 1)
                            <b class="text-danger">habis</b>
                            @else
                            {{ $the_product->stocks->stock }} {{ $the_product->unit->unit }}
                            @endif
                            @else
                            <b class="text-danger">habis</b> @endisset</h4>
                        <form action="{{ route('addtocart') }}" method="POST">
                            @csrf
                            <div class="form-group d-flex">
                                <input id="dataproduct" name="dataproduct" type="hidden" value="{{$the_product->id}}" required>
                                <a id="minvaltocart" href="#" style="width: 30px;height:30px;" class="btn btn-sm btn-white rounded-circle m-2">-</a>
                                <input id="valtocart" class="form-control m-2" name="valbuy" style="width: 70px;height:35px;" min="1" type="number" value="1" required>
                                <a id="addvaltocart" href="#" style="width: 30px;height:30px;" class="btn btn-sm btn-success rounded-circle m-2">+</a>                               
                            </div>
                            <div class="faction text-right mb-2 mt-auto">
                                <button type="submit" id="add2cart" class="btn btn-success"><i class="fa fa-cart-plus"></i> tambah ke keranjang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        let val_tc = $('#valtocart').val();
        $('#addvaltocart').on('click',function(e){
            e.preventDefault();
            val_tc = Number(val_tc) + 1;
            $('#valtocart').val(val_tc);
        });
        $('#minvaltocart').on('click',function(e){
            e.preventDefault();
            if(val_tc != 1){
                val_tc = Number(val_tc) - 1;
                $('#valtocart').val(val_tc);
            }
        });
        $('#add2cart').click((e) => {
            console.log('asdasd');
        });
    </script>
    
@endpush