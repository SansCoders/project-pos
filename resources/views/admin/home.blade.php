@extends('dashboard-layout.master')
@section('header-content')
<!-- Header -->
<div class="header pb-6 d-flex align-items-center" >
  <span class="mask bg-primary opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
          <br/>
          <!-- Card stats -->
          <div class="row">
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Product</h5>
                      @php
                          $countProduct = App\Product::count();
                      @endphp
                      <span class="h2 font-weight-bold mb-0">{{$countProduct}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="fa fa-box"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total users</h5>
                      @php
                          $countSales = App\User::count();
                          $countCashiers = App\Cashier::count();
                      @endphp
                      <span class="h2 font-weight-bold mb-0">{{$countSales+ $countCashiers}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="ni ni-chart-pie-35"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Transactions</h5>
                      @php
                          $countFaktur = App\Faktur::count();
                      @endphp
                      <span class="h2 font-weight-bold mb-0">{{$countFaktur}}</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                        <i class="ni ni-cart"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            {{-- <div class="col-xl-3 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Performance</h5>
                      <span class="h2 font-weight-bold mb-0">49,65%</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                        <i class="ni ni-chart-bar-32"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div> --}}
          </div>
        </div>
      </div>
    </div>
@endsection
@section('content')
<div class="container-fluid mt--5">
    <div class="row mr-2 ml-2">
        <div class="col-lg-8 order-lg-1 order-xl-1">
            <div class="row align-items-center justify-content-start">
            <strong class="h1 text-dark">Welcome {{ Auth::user()->name }}</strong>
            </div>
            <div class="row">
                <div class="card col-lg bg-translucent-default">
                    <div class="card-body row">
                        <div class="col-lg-4">
                          <a href="{{route('admin.categorys')}}">
                            <div class="card shadow-none text-center card-body d-flex flex-row align-items-center">
                              <i class="fa fa-plus-circle text-success"></i>&nbsp;
                              <strong class="text-dark">TAMBAH KATEGORI</strong>
                            </div>
                          </a>
                        </div>
                        <div class="col-lg-4">
                          <a href="{{route('admin.users-cashier')}}">
                            <div class="card shadow-none text-center card-body d-flex flex-row align-items-center">
                              <i class="fa fa-plus-circle text-success"></i>&nbsp;
                              <strong class="text-dark">TAMBAH KASIR</strong>
                            </div>
                          </a>
                        </div>
                        <div class="col-lg-4">
                          <a href="{{route('admin.users-sales')}}">
                            <div class="card shadow-none text-center card-body d-flex flex-row align-items-center">
                              <i class="fa fa-plus-circle text-success"></i>&nbsp;
                              <strong class="text-dark">TAMBAH SALES</strong>
                            </div>
                          </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 order-lg-2 order-xl-2">
            {{-- <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection