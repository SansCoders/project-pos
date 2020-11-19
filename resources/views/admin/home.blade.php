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
            <div class="col-xl-4 col-md-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Produk</h5>
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
            <div class="col-xl-4 col-md-6">
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
            <div class="col-xl-4 col-md-6">
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
          </div>
        </div>
      </div>
    </div>
@endsection
@section('content')
<div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-8">
          <div class="card bg-default">
            <div class="card-header bg-transparent">
                <div class="row align-items-center">
                  <div class="col">
                    <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                    <h5 class="h3 text-white mb-0">Total Transactions</h5>
                  </div>
                  <div class="col">
                    <ul class="nav nav-pills justify-content-end">
                      <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales-dark" data-update="{&quot;data&quot;:{&quot;datasets&quot;:[{&quot;data&quot;:[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}" data-prefix="$" data-suffix="k">
                        <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                          <span class="d-none d-md-block">Mingguan</span>
                          <span class="d-md-none">M</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            <div class="card-body"> 
                <div class="chart">
                    <!-- Chart wrapper -->
                    <canvas id="chart-a-dark" class="chart-canvas"></canvas>
                </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                  <h5 class="h3 mb-0">Recently Transactions</h5>
                </div>
              </div>
            </div>
            <div class="card-body">
              @php
                $RecentlyTransaction = App\Receipts_Transaction::where('status','confirmed')->orderBy('created_at', 'desc')->take(5)->get();
              @endphp 
              @foreach ($RecentlyTransaction as $RT)
                <div class="card-body shadow-none--hover shadow-sm d-flex justify-content-between">
                  <strong>
                    #{{$RT->transaction_id}}
                  </strong>
                  {{$RT->user_name}}
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-8">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">List Sales</h3>
                </div>
                <div class="col text-right">
                  <a href="{{route('admin.users-sales')}}" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush table-hover">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Last Transaction</th>
                    <th scope="col"class="text-center">Total Transaction</th>
                  </tr>
                </thead>
                <tbody>
                  @if (count($sales) < 1)
                      <tr>
                        <td colspan="3" class="text-center">no records</td>
                      </tr>
                  @endif
                  @foreach ($sales as $user)
                    @php
                        $countTotalTransaction = App\Receipts_Transaction::where('user_id',$user->id)->where('order_via',3)->get();
                        $getLastTransaction = App\Receipts_Transaction::where('user_id',$user->id)->where('order_via',3)->orderBy('done_time','desc')->first();
                    @endphp  
                    <tr>
                      <th scope="row">
                        {{$user->name}}
                      </th>
                      <td>
                        @isset($getLastTransaction->done_time)
                        {{$getLastTransaction->done_time}}
                        @endisset
                      </td>
                      <td class="text-center">
                        {{count($countTotalTransaction)}}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Stock traffic</h3>
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-default btn-icon-only" data-container="body" data-html="true" data-toggle="popover" 
                    data-placement="bottom" data-content="<div>
                                    <i class='fas fa-arrow-up text-success mr-3'></i> : Masuk (in)
                                    </div>
                                    <div><i class='fas fa-arrow-down text-danger mr-3'></i> : Keluar (out)</div>
                                    <div><i class='fas fa-clock text-warning mr-3'></i> : Pending (out)</div>">
                      <i class="fa fa-question-circle"></i>
                    </button>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Product</th>
                    <th scope="col">time</th>
                    <th scope="col">stock</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                      $activitiesStock = App\StockActivity::orderBy('created_at', 'desc')->take(5)->get();
                  @endphp
                    @foreach ($activitiesStock as $a_stock)
                        <tr>
                          <th scope="row">{{$a_stock->product->nama_product}}</th>
                          <td>{{Carbon\Carbon::parse($a_stock->created_at)->diffForHumans()}}</td>
                          <td>
                            @if ($a_stock->type_activity == "in" || $a_stock->type_activity == "add")
                              <i class="fas fa-arrow-up text-success mr-3"></i>
                              @elseif($a_stock->type_activity == "out")
                              <i class="fas fa-arrow-down text-danger mr-3"></i>
                              @elseif($a_stock->type_activity == "pending")
                              <i class="fas fa-clock text-warning mr-3"></i>
                              @elseif($a_stock->type_activity == "destroy")
                              <i class="fas fa-trash text-warning mr-3"></i>
                            @endif
                            {{$a_stock->stock}}
                          </td>
                        </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
        </div>
        </div>
      </div>
@endsection
@php
    setlocale(LC_TIME, 'id');
    $countTransactionWeek = [];
    $datenow = date('Y-m-d');
    $lastDayWeek = date('Y-m-d',strtotime($datenow . "-6 days"));
    for ($i = 0; $i < 7; $i++) {
        $cekData = App\Receipts_Transaction::whereRaw("date(created_at) = date(now()) - INTERVAL $i DAY");
        $countTransactionWeek[$i] = $cekData->count();
    }
    $period = \Carbon\CarbonPeriod::create($lastDayWeek,$datenow);

    $categories = App\CategoryProduct::all();
    if(count($categories) < 1){
      $categProductCount  = [];
      $categLabels   = [];
    }
    foreach ($categories as $key => $categ) {
      $categLabels[$key] = $categ->name;
      $categProductCount[$key] = DB::table('products')->where('category_id', $categ->id)->count();
    }
@endphp
@push('scripts')
    <script>
        $('#inputkode').keyup(function(){
            var v = $(this).val();
            if(v.length >= 3){
                $('.product-details').html(v);
            }
        });
        var ctx = document.getElementById('chart-a-dark');
        var chart2 = document.getElementById('chart-second');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                datasets: [{
                    data: JSON.parse('<?php echo json_encode($countTransactionWeek) ?>').reverse(),
                    label: 'total transactions',
                    yAxisID: 'left-y-axis'
                }],
                labels: [
                    '@foreach ($period as $date) {{$date->formatLocalized('%A')}} ','@endforeach'
                ],
            },
            options: {
                scales: {
                    yAxes: [{
                        id: 'left-y-axis',
                        type: 'linear',
                        position: 'left',
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)",
                        }
                    }]
                }
            }
        });

        var myDoughnutChart = new Chart(chart2, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                      '@foreach ($categProductCount as $ct) {{$ct}} ','@endforeach'
                    ]
                }],
                labels: [
                  '@foreach ($categLabels as $lc) {{$lc}} ','@endforeach'
                ]
            },
        });
    </script>
@endpush