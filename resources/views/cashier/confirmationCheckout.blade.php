@extends('dashboard-layout.master')

@section('content')

    @php
        $p_id = json_decode($transaction->products_id);
        $p_l = json_decode($transaction->products_list);
        $p_bv = json_decode($transaction->products_buyvalues);
        $p_prices = json_decode($transaction->products_prices);
        
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
                                        <h5>@currency($data['price'][$i])</h5>
                                    </div>
                                </div>
                                
                                @php
                                    $priceTotal += $data['price'][$i];
                                @endphp
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
                        <button type="submit" class="btn btn-success btn-block btn-lg">KONFIRMASI SUDAH BAYAR</button>
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
                                <span class="font-weight-light">Lorem ipsum dolor sit amet consectetur adipisicing elit. Vel, deserunt quo similique, eum dicta itaque quos exercitationem veniam, repellendus soluta eius iste possimus?</span>
                            </div>
                        </div>
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
                                <span class="badge badge-primary badge-pill ml-auto">{{$data['buy_value'][$i]}} {{$product->unit->unit}}</span>
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
@endsection