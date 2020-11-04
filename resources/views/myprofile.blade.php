@extends('dashboard-layout.master')

@section('header-content')
    <div class="header py-5">
        <div class="container row">
            <div class="col-xl-4 col-md-6">
                <div class="card card-stats shadow-sm--hover shadow-none">
                    <div class="card-body">
                        <div class="row">
                          <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Transactions</h5>
                            <span class="h2 font-weight-bold mb-0">23</span>
                          </div>
                          <div class="col-auto">
                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                              <i class="ni ni-active-40"></i>
                            </div>
                          </div>
                        </div>
                        <p class="mt-3 mb-0 text-sm">
                          <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                          <span class="text-nowrap">Since last month</span>
                        </p>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid mt-6">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-header">
                    asd
                </div>
            </div>
        </div>
    </div>
@endsection