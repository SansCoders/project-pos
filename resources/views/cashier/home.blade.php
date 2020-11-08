@extends('dashboard-layout.master')

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
            <div class="card shadow-none bg-warning">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="h3 text-white mb-0">1 product stock habis</span>
                    </div>
                    <strong class="text-white"><i class="fa fa-arrow-alt-circle-right text-white mr-2"></i>cek</strong>
                </div>
            </div>
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
    </div>
</div>

<div class="modal fade" id="listproductsModal" tabindex="-1" role="dialog" aria-labelledby="listproductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="listproductsModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            s
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
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
                    data: [10, 20, 30]
                }],
                labels: [
                    'Red',
                    'Yellow',
                    'Blue'
                ]
            },
        });
    </script>
@endpush