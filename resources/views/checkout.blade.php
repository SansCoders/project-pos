@extends('dashboard-layout.master')

@section('content')

<div class="container-fluid my-4">
        <a href="{{ url()->previous() }}" class="btn btn-neutral">back</a>
        <h2 class="mb-4"><i class="fa fa-shopping-cart"></i> Keranjang</h2>
        <div class="row">
            <div class="col-lg-8">
                @if ($message = Session::get('err'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                @if ($cart->count() == 0)
                    <div class="text-center">
                        <h2 class="text-muted">keranjang masih kosong</h2>
                    </div>
                @endif
                @foreach ($cart as $item)
                    <div class="card shadow-none">
                        <div class="card-body d-flex align-items-center flex-wrap">
                            <img class="img-thumbnail mr-2" style="max-width: 120px" src="{{ asset($item->product->img) }}" alt="">
                            <a href="{{route('details.product',$item->product->slug)}}" class="font-weight-bold text-dark">{{$item->product->nama_product}}</a>
                            <div class="d-flex ml-auto mr-0 justify-content-between">
                                <h3 class="text-muted">{{ $item->buy_value }} {{ $item->product->unit->unit }}</h3>
                                {{-- <button class="btn btn-icon text-success mr-1">+</button>
                                <input type="number" style="max-width: 60px;min-width: 60px" class="form-control" value="{{$item->buy_value}}">
                                <button class="btn btn-icon ml-1">-</button> --}}
                                <button type="button" class="btn text-muted ml-5"><i class="fa fa-pencil-alt"></i></button>
                                <form action="{{ route('checkout.destroy', $item->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    {{-- <button class="btn btn-danger" type="submit">Delete</button> --}}
                                    <button type="submit" class="btn btn-icon text-danger ml-auto"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4">
                <div class="card bg-gradient-white border-default shadow-none" style="border: 1px solid black">
                    <div class="card-body">
                        <h3 class="font-weight-bold text-center mb-3">
                            Order Summary
                        </h3>
                        <div class="d-flex flex-column">
                            <div class="row mb-2 mt-4">
                                <div class="col-lg-6 font-weight-bold">product</div>
                                <div class="col-lg-6 d-flex justify-content-between">
                                    <h4 class="font-weight-300 text-center">Qty</h4>
                                    <h5 class="font-weight-normal text-center">price</h5>
                                    <h5 class=" text-center">total</h5>
                                </div>
                            </div>
                            @foreach ($cart as $item)
                                <div class="product row">
                                    <div class="col-lg-6">
                                        <h4 class="font-weight-300">{{$item->product->nama_product}}</h4>
                                    </div>
                                    <div class="col-lg-6 d-flex justify-content-between">
                                        <h4 class="font-weight-300">{{$item->buy_value}}</h4>
                                        <h5 class="font-weight-normal">@currency($item->product->price)</h5>
                                        <h5>@currency($item->buy_value * $item->product->price)</h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-3">
                        <div class="font-weight-bold text-center justify-content-between d-flex">
                            <h2>Total</h2>
                            <h2>
                            @php
                                $priceTotal = 0;
                            @endphp
                            @foreach ($cart as $item)
                                @php
                                    $priceTotal += ($item->buy_value * $item->product->price);
                                @endphp
                            @endforeach
                                @currency($priceTotal)
                            </h2>
                        </div>
                    </div>
                </div>
                @if ($cart->count() > 0)
                    <button class="btn btn-primary w-100" data-toggle="modal" data-target="#proses">kirim ke kasir</button>
                @endif
            </div>
        </div>
    </div>

    @if ($cart->count() > 0)
        <div class="modal fade" id="proses" tabindex="-1" role="dialog" aria-labelledby="proses" aria-hidden="true">
            <div class="modal-dialog modal-dark modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-dark">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-notification"></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="py-3 text-center">
                            <i class="ni ni-cart ni-3x"></i>
                            <h4 class="heading mt-4">Konfirmasi Checkout</h4>
                            <p>Pastikan produk yang akan beli sudah benar, jika sudah yakin silahkan klik tombol <b>kirim</b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('checkout.process') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-white">Kirim</button>
                        </form>
                        <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection