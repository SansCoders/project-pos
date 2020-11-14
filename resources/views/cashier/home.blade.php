@extends('dashboard-layout.master')

@section('add-css')
    <style>
      .newTransaction{
        position: fixed;
        bottom: 12px;
        right: 12px;
      }
      .newTransaction button{
        width: 4rem;
        height: 4rem;
      }
    </style>
@endsection

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 150px; background-size: cover; background-position: center top;">
        <span class="mask bg-primary"></span>
</div>
<div class="container-fluid mt--8">
    <div class="row">
        <div class="col-lg-6 col-md-6 text-center">
        @if ($transactionPending->count() > 0)
            <a href="{{route('cashier.transaction')}}">
                <div class="card shadow-none bg-default">
                    <div class="card-body">
                        <div class="mb-3">
                            <strong class="h3 text-white"><span class="text-yellow">{{$transactionPending->count()}} transaksi</span> menunggu diproses</strong>
                        </div>
                        <strong class="text-white"><i class="fa fa-arrow-alt-circle-right text-white mr-2"></i> cek</strong>
                    </div>
                </div>
            </a>
        @else    
            <div class="card shadow-none bg-success">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <i class="fa fa-check-circle text-white mr-2"></i>
                    <span class="h3 text-white mb-0">semua transaksi sudah diproses</span>
                </div>
            </div>
        @endif
        </div>
        <div class="col-lg-6 col-md-6 text-center">
          @if ($stockCount > 0)
          <a href="#">
            <div class="card shadow-none bg-warning">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="h3 text-white mb-0">{{$stockCount}} product stock habis</span>
                    </div>
                    <strong class="text-white"><i class="fa fa-arrow-alt-circle-right text-white mr-2"></i>cek</strong>
                </div>
            </div>
          </a>
          @else
            <div class="card shadow-none bg-success">
              <div class="card-body d-flex align-items-center justify-content-center">
                  <i class="fa fa-check-circle text-white mr-2"></i>
                  <span class="h3 text-white mb-0">semua stock masih ada</span>
              </div>
            </div>
          @endif
        </div>
        <div class="col-lg-12">
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
                          {{-- <li class="nav-item" data-toggle="chart" data-target="#chart-sales-dark" data-update="{&quot;data&quot;:{&quot;datasets&quot;:[{&quot;data&quot;:[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}" data-prefix="$" data-suffix="k">
                            <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                              <span class="d-none d-md-block">Bulanan</span>
                              <span class="d-md-none">B</span>
                            </a>
                          </li> --}}
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
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    Categories
                </div>
                  <div class="card-body"> 
                    <div class="chart">
                        <!-- Chart wrapper -->
                        <canvas id="chart-second" class="chart-canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
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
                  <!-- Projects table -->
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
</div>

<div class="newTransaction">
  <a href="{{ route('cashier.newtransaction') }}">
    <button class="btn btn-success btn-icon-only"><i class="fa fa-cart-plus"></i></button>
  </a>
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