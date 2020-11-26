@extends('dashboard-layout.master')

@section('add-css')
    <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('content')
@php
    $priceTotal = 0;
@endphp
<div class="container-fluid mt-5 pb-5">
    <div class="d-flex mb-4 align-items-center">
        <a href="{{ route('cashier.newtransaction') }}" class="btn btn-neutral"><i class="fa fa-arrow-alt-circle-left"></i></a>
        <h2 class="ml-2 mb-0"><i class="fa fa-shopping-cart"></i> Keranjang</h2>
    </div>
    <div class="row">
        <div class="col-lg-8 mb-n-5">
            
            @if (session()->get('error'))
            <div class="alert alert-danger">
                {{session()->get('error')}}
            </div>
            @endif
            @if (session()->get('success'))
                <div class="alert alert-success">
                    {{session()->get('success')}}
                </div>
            @endif
            @if ($message = Session::get('err'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button> 
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            @if ($data->count() == 0)
                <div class="text-center">
                    <h2 class="text-muted">keranjang masih kosong</h2>
                </div>
            @endif
            
            @foreach ($data as $item)
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img style="max-width: 150px" src="{{ asset($item->product->img) }}" alt="">
                        </div>
                        <div class="col ml-2">
                            <h4 class="mb-1">
                                {{$item->product->nama_product}}
                            </h4>
                            <b class="text-sm">
                            @if (($item->buy_value * $item->product->price) != $item->custom_price)
                               <s class="text-red">@currency($item->buy_value * $item->product->price)</s> @currency($item->custom_price)
                                @php
                                    $priceTotal += $item->custom_price;
                                @endphp
                            @else    
                                @currency($item->product->price*$item->buy_value)
                                @php
                                    $priceTotal += ($item->product->price*$item->buy_value);
                                @endphp
                            @endif
                            </b><br/>
                            <small>Quantitiy : {{$item->buy_value}}</small>
                        </div>
                        <div class="d-flex flex-column mt-2 mt-md-0">
                            <button class="btn btn-info editHarga" data-icp="{{$item->id}}" data-toggle="modal" data-target="#customPrice">Custom Price</button>
                            <div class="row justify-content-center mt-3">
                                <button class="btn btn-sm editQty" data-icp="{{$item->id}}" data-toggle="modal" data-target="#editQty">edit</button>
                                <form action="{{ route('cashier.checkout.destroy', $item->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-sm text-danger ml-auto"><i class="fa fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="col-lg-4">
            <div class="position-sticky top-4">
                <div class="card bg-gradient-white border-default shadow-none" style="border: 1px solid black">
                    <div class="card-body">
                        <h2 class="font-weight-bold text-center mb-3">
                            Keranjang
                        </h2>
                        <hr class="my-3">
                        <div class="font-weight-bold text-center justify-content-between d-flex">
                            <h2>Total</h2>
                            <h2>
                                @currency($priceTotal)
                            </h2>
                        </div>
                    </div>
                </div>
                @if ($data->count() === 1)
                    <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#confirmCheckout">KONFIRMASI & CETAK FAKTUR</button>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($data->count() === 1)
<div class="modal fade" id="confirmCheckout" tabindex="-1" role="dialog" aria-labelledby="confirmCheckout" aria-hidden="true">
    <div class="modal-dialog modal-dark modal-dialog-centered" role="document">
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
                    <p>Pastikan produk yang akan beli sudah benar, jika sudah yakin silahkan klik tombol <b>konfirmasi</b></p>
                </div>
                
            </div>
            
            <form action="{{ route('cashier.confirm.viacashier') }}" method="post">
                @csrf
            <div class="modal-body">
                <div class="form-group text-center">
                    Pilih Satu
                </div>
                <div class="form-group">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" name="user_type" value="cns" required>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="buyer_name"placeholder="Untuk Pelanggan Baru">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <input type="radio" name="user_type" value="cr" required>
                            </div>
                        </div>
                        <select name="user_registered" id="" class="form-control">
                            <option disabled selected>Untuk Pelanggan Terdaftar</option>
                            @php
                                $UserList = App\User::where('status',1)->get();
                            @endphp
                            @if (count($UserList) > 0)
                                @foreach ($UserList as $customer)
                                    <option value="{{ $customer->id }}"><strong>{{$customer->name}}</strong> @isset($customer->phone)({{$customer->phone}})@endisset</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-white">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
<div class="modal fade" id="editQty" tabindex="-1" role="dialog" aria-labelledby="editQtyLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editQtyLabel">Ubah Quantity</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="contentEditQty">
          Mohon Tunggu
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="customPrice" tabindex="-1" role="dialog" aria-labelledby="customPriceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="customPriceLabel">Ubah Harga</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="contentcustomPrice">
          <i class="fa fa-spinner fa-spin"></i> Mohon Tunggu
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
    <script>
        $('.editQty').click(function(){
                var idCart = $(this).data("icp");
                $.ajax({
                    url : "/cashier/editcartqty",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idCart" : idCart},
                    success: function(resp){
                        $('#contentEditQty').html(resp);
                    }
                });
        });
        $('.editHarga').click(function(){
                var idCart = $(this).data("icp");
                $.ajax({
                    url : "/cashier/editcartprice",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idCart" : idCart},
                    success: function(resp){
                        $('#contentcustomPrice').html(resp);
                    }
                });
        });
    </script>
@endpush