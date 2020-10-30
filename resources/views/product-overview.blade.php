@extends('dashboard-layout.master')
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
    <nav class="header bg-gradient-gray mb-4">
        <div class="container">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-dark mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{$the_product->category->name}}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$the_product->nama_product}}</li>
                            </ol>
                          </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        <button class="btn btn-icon btn-primary">
                            <span class="btn-inner--icon"><i class="fa fa-cash-register"></i></span>
                            <span class="btn-inner--text">Checkout</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
        <div class="container">
            <div class="card shadow-none">
                <div class="card-body row">
                    <div class="col-lg-6 col-sm-6 mb-4">
                        <img src="{{asset($the_product->img)}}" class="img-fluid rounded" alt="gambar {{$the_product->nama_product}}" />
                    </div>
                    <div class="col-lg-6 col-sm-6 d-flex flex-column">
                        <h2>
                            {{$the_product->nama_product}}
                        </h2>
                        <span class="badge badge-white text-left mt-2">{{$the_product->category->name}}</span>
                        <span class="mt-2">
                            {!! $the_product->description !!}
                        </span>
                        <form action="" method="POST">
                            @csrf
                            <div class="form-group d-flex">
                                <input id="valtocart" class="form-control m-2" style="width: 70px" min="1" type="number" value="1">
                                <a id="addvaltocart" href="#" class="btn btn-outline-success rounded-circle m-2">+</a>
                                <a id="minvaltocart" href="#" class="btn btn-white rounded-circle m-2">-</a>
                            </div>
                            <div class="faction text-right mb-0 mt-auto">
                                <button class="btn btn-success"><i class="fa fa-cart-plus"></i> tambah ke keranjang</button>
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
        $('#addvaltocart').on('click',function(){
            val_tc = Number(val_tc) + 1;
            $('#valtocart').val(val_tc);
        });
        $('#minvaltocart').on('click',function(){
            if(val_tc != 1){
                val_tc = Number(val_tc) - 1;
                $('#valtocart').val(val_tc);
            }
        });
        
    </script>
    
@endpush