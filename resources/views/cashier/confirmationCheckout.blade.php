@extends('dashboard-layout.master')

@section('content')

    @php
        $p_id = json_decode($transaction->products_id);
        $p_l = json_decode($transaction->products_list);
        $p_bv = json_decode($transaction->products_buyvalues);
        $p_prices = json_decode($transaction->products_prices);
        $tp_prices = json_decode($transaction->total_productsprices);
        $cp_prices = json_decode($transaction->custom_prices);
        
        $i = 0;
        foreach ($p_l as $item) {
            $data['product'][$i] = $item;
            $i++;
        }
        $i = 0;
        foreach ($p_prices as $item) {
            $data['price'][$i] = $item;
            $i++;
        }
        $i = 0;
        foreach ($p_bv as $item) {
            $data['buy_value'][$i] = $item;
            $i++;
        }
        $i = 0;
        foreach ($tp_prices as $item) {
            $data['tp_prices'][$i] = $item;
            $i++;
        }
        $i = 0;
        // for ($i=0; $i < count($cp_prices); $i++) { 
        //     $data['cp_prices'][$i] = $item;
        //     $data['tcp_prices'][$i] = $item * $data['buy_value'][$i];
        // }
        // $i = 0;
        foreach ($cp_prices as $index => $item) {
            $data['cp_prices'][$i] = $item;
            // $data['tcp_prices'][$i] = $item * $p_bv[$index];
            $data['tcp_prices'][$i] = $item;
            $i++;
        }
    @endphp
    <div class="container-fluid">
    <h1 class="mb-4">Order Id #{{ $transaction->transaction_id }}</h1>
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-4">
                <div class="card shadow-sm card-profile">
                    <div class="card-body">
                        <h3 class="font-weight-bold text-center mb-3">
                            Order Summary
                        </h3>
                        <div class="d-flex flex-column">
                            <div class="row mb-2 mt-4">
                                <div class="col-lg-6 font-weight-bold">product</div>
                                <div class="col-lg-6 d-flex justify-content-between">
                                    <h4 class="font-weight-300 text-center">Qty</h4>
                                    <h5 class=" text-center">total</h5>
                                </div>
                            </div>
                            
                            @php
                                $priceTotal = 0;
                            @endphp
                            @for ($i = 0; $i < count($data['product']); $i++)
                                <div class="product row">
                                    <div class="col-lg-6">
                                        <h4 class="font-weight-300">{{$data['product'][$i]}}</h4>
                                    </div>
                                    <div class="col-lg-6 d-flex justify-content-between">
                                        <h4 class="font-weight-300">{{$data['buy_value'][$i]}}</h4>
                                        <h5>
                                        @if ($data['tcp_prices'][$i] != $data['tp_prices'][$i])
                                            @currency($data['tcp_prices'][$i])
                                            @php
                                                $priceTotal += $data['tcp_prices'][$i];
                                            @endphp
                                        @else
                                            @currency($data['price'][$i] * $data['buy_value'][$i])
                                            @php
                                                $priceTotal += $data['tp_prices'][$i];
                                            @endphp
                                        @endif</h5>
                                    </div>
                                </div>
                            @endfor
                            <hr class="my-3">
                            <div class="font-weight-bold text-center justify-content-between d-flex">
                                <h2>Total</h2>
                                <h2>
                                    @currency($priceTotal)
                                </h2>
                            </div>
                        </div>    
                    </div>    
                </div>
                <div class="mt-5">
                    <form action="{{ route('cashier.confirm.checkout',$transaction->transaction_id) }}" method="post">
                        @csrf
                        <input type="hidden" name="_orderid" value="{{$transaction->transaction_id}}">
                        <div class="form-group custom-control custom-checkbox">
                            <input type="checkbox" name="check_approve" class="custom-control-input" id="check_approve" required>
                            <label class="custom-control-label" for="check_approve">data sudah benar <sup class="text-danger">*</sup> </label>
                        </div>
                        <button type="submit" class="btn btn-success btn-block btn-lg">KONFIRMASI</button>
                    </form>
                    <form action="{{ route('cashier.confirm.checkout-canceled',$transaction->id) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PUT')
                        {{-- <input type="hidden" name="_orderid" value="{{$transaction->transaction_id}}"> --}}
                        <button type="submit" class="btn btn-danger btn-block btn-lg">HAPUS RECEIPT, DATA TIDAK SESUAI</button>
                    </form>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card shadow-sm bg-yellow">
                    <div class="card-body d-flex align-items-center">
                        @php
                            $buyer = App\User::where('id',$transaction->user_id)->first();  
                             
                        @endphp  
                        <img alt="Image placeholder" src="{{ asset('assets/img/theme/team-1.jpg')}}" class="avatar avatar-sm rounded-circle">
                        <a href="#" class="ml-4 text-dark h1">{{$buyer->name}}</a>
                        <button type="button" class="ml-auto btn btn-info btn-icon-only" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <span class="btn-inner--icon"><i class="fa fa-info"></i></span>
                        </button>
                    </div>
                    <div class="collapse" id="collapseExample">
                        <div class="card-body row">
                        <div class="col-lg-6">
                            <div class="card shadow-none card-body d-flex flex-column bg-white">
                                <span class="font-weight-bold"><i class="fa fa-phone"></i> no. telepon</span>
                                <span class="font-weight-light">{{$buyer->phone}}</span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card shadow-none card-body d-flex flex-column bg-white">
                                <span class="font-weight-bold"><i class="fa fa-map-marked"></i> Alamat</span>
                                <span class="font-weight-light">{{$buyer->address}}</span>
                            </div>
                        </div>
                        </div>
                      </div>
                </div>
                <div class="card">
                    <div class="card-header p-1">
                        <h4 class=" m-0">Bukti Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @if (count(App\BuktiTransfer::getBuktiTF($transaction->id)) == 0)
                                <div class="alert alert-secondary">belum ada bukti pembayaran.</div>
                            @endif
                            @foreach (App\BuktiTransfer::getBuktiTF($transaction->id) as $item)
                            <a href="{{asset($item->bukti_transfer_image_path)}}" class="list-group-item list-group-item-action d-flex justify-content-between" target="_blank">
                                <div class="">{{$item->keterangan}}</div>
                                <div class="">
                                    <h6 class="badge badge-sm badge-secondary">{{$item->created_at}}</h6>
                                </div>
                            </a>
                            @endforeach
                          </div>
                        <div class="d-flex flex-wrap">
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <ul class="list-group list-group-flush" data-toggle="checklist">
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($p_id as $item)
                            @php
                                $product = App\Product::where('id',$item)->first();    
                            @endphp                            
                            <li class="list-group-item d-flex align-items-center">
                                <img class="img-thumbnail" src="{{asset(($product->img))}}" alt=""  style="max-width: 120px">
                                <div class="d-flex flex-column ml-3">
                                    <h4 class="text-muted">{{$product->kodebrg}}</h4>
                                    <h4>{{$product->nama_product}}</h4>
                                </div>
                                <div class="ml-auto text-right">
                                    <h4 class="text-muted">(
                                        {{-- @if (($product->price * $data['buy_value'][$i]) != $data['cp_prices'][$i])
                                            <s>@currency($product->price)</s> <span class="text-danger">@currency($data['cp_prices'][$i])</span> 
                                        @else --}}
                                            @currency($product->price)    
                                        {{-- @endif --}}
                                        {{-- @currency($data['cp_prices'][$i])     --}}
                                         x {{$data['buy_value'][$i]}} ) 

                                         {{-- @if ($data['tcp_prices'][$i] != ($product->price * $data['buy_value'][$i])) --}}
                                         @if ($data['tcp_prices'][$i] != $data['tp_prices'][$i])
                                            <s>@currency($data['tp_prices'][$i])</s>
                                            <span class="text-danger">
                                                @currency($data['tcp_prices'][$i])
                                            </span>
                                        @else
                                            @currency($data['tp_prices'][$i])
                                        @endif
                                    </h4>
                                    
                                    <button class="btn ml-2 btn-info btn-sm editHarga" data-ip="{{$item}}" data-idreceipt="{{$transaction->transaction_id}}" data-toggle="modal" data-target="#customPrice">ubah harga</button>
                                    {{-- <span class="badge badge-primary badge-pill">{{$data['buy_value'][$i]}} {{$product->unit->unit}}</span> --}}
                                    {{-- <button class="btn ml-2 btn-neutral btn-sm">beri harga khusus</button> --}}
                                    {{-- <button class="btn ml-2 btn-neutral btn-sm">beri diskon</button> --}}
                                </div>
                            </li>
                            @php
                                $i += 1;
                            @endphp
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customPrice" tabindex="-1" role="dialog" aria-labelledby="customPriceLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="customPriceLabel">Ubah Total Harga</h5>
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
    <script>
        $('.editHarga').click(function(){
                var idProduct = $(this).data("ip");
                var idReceipt = $(this).data("idreceipt");
                $.ajax({
                    url : "/cashier/editreceiptprice",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idProduct" : idProduct,"idReceipt" : idReceipt},
                    success: function(resp){
                        $('#contentcustomPrice').html(resp);
                    }
                });
        });
    </script>
    
@endpush