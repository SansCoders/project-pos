@extends('dashboard-layout.master')
@include('dashboard-layout.footer')
@section('content')

<div class="container-fluid mt-5 pb-5">
        <div class="d-flex mb-4 align-items-center">
            <a href="{{ url('home') }}" class="btn btn-neutral"><i class="fa fa-arrow-alt-circle-left"></i></a>
            <h2 class="ml-2 mb-0"><i class="fa fa-shopping-cart"></i> Keranjang</h2>
        </div>
        <div class="row">
            <div class="col-lg-8 mb-n-5">
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
                <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <img style="max-width: 150px" src="{{ asset($item->product->img) }}" alt="">
                        </a>
                    </div>
                    <div class="col ml--2">
                        <h4 class="mb-1">
                            <a href="{{route('details.product',$item->product->slug)}}" class="font-weight-bold text-dark">{{$item->product->nama_product}}</a>
                        </h4>
                        @if ($item->custom_price != $item->product->price)
                           <s><b class="text-sm">@currency($item->buy_value * $item->product->price)</b></s>
                           <b class="text-sm text-danger">@currency($item->buy_value * $item->custom_price)</b>
                           <br/>
                        @else    
                            <b class="text-sm">@currency($item->buy_value * $item->product->price)</b><br/>
                        @endif
                        <small>Quantitiy : {{$item->buy_value}}</small>
                    </div>
                    <button class="btn btn-primary editQty" data-icp="{{$item->id}}" data-toggle="modal" data-target="#exampleModal">edit</button>
                    <form action="{{ route('checkout.destroy', $item->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon text-danger ml-auto"><i class="fa fa-trash"></i> Hapus</button>
                    </form>
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
                                @php
                                    $priceTotal = 0;
                                @endphp
                                @foreach ($cart as $item)
                                    @php
                                        if($item->custom_price != $item->product->price)
                                        {
                                            $priceTotal += ($item->buy_value * $item->custom_price);
                                        }else{
                                            $priceTotal += ($item->buy_value * $item->product->price);
                                        }
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
                            <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-white">Kirim</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Ubah Quantity</h5>
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

        $('.editQty').click(function(){
                var idCart = $(this).data("icp");
                $.ajax({
                    url : "/editcartqty",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idCart" : idCart},
                    success: function(resp){
                        $('#contentEditQty').html(resp);
                    }
                });
            });
    </script>
    
@endpush