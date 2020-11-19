@extends('dashboard-layout.master')

@section('header-content')
    <div class="header py-5">
      <span class="mask bg-primary opacity-1"></span>
        <div class="container-fluid d-flex align-items-center justify-content-center">
            <img src="{{asset($user->profile->photo)}}" style="border-radius: 10px; max-width:200px" alt="">
            <div class="card bg-transparent shadow-none ml-2">
              <div class="card-body">
                <h1 class="mb-5">{{ $user->name }}</h1>
                <span class="badge badge-default badge-lg">
                  <i class="fa fa-phone-alt"></i>
                  <strong>{{ $user->phone }}</strong>
                </span>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid mt-4">
      <div class="container  text-center">
        <strong class="h1 font-weight-bolder mb-5">Summary</strong>
        <div class="row d-flex align-items-center justify-content-around mt-4">
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-translucent-warning shadow-sm--hover shadow-none text-center">
              <div class="card-body">
                      <h5 class="card-title text-uppercase font-weight-bolder text-white mb-0">Total Transactions</h5>
                      <span class="h2 font-weight-bold text-white mb-0">{{$myTransaction->count()}}</span>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-translucent-warning shadow-sm--hover shadow-none text-center">
              <div class="card-body">
                  <h5 class="card-title text-uppercase font-weight-bolder text-white mb-0">Registered At</h5>
                  <span class="h2 font-weight-bold text-white mb-0">{{date('d-M-Y',strtotime($user->created_at))}}</span>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card card-stats bg-translucent-warning shadow-sm--hover shadow-none text-center">
              <div class="card-body">
                      <h5 class="card-title text-uppercase font-weight-bolder text-white mb-0">Last Transaction</h5>
                      @php
                         $getLastTransaction = $myTransaction->where('is_done',1)->orderBy('done_time', 'DESC')->first();
                      @endphp
                      @if ($getLastTransaction == null)
                      <span class="h2 font-weight-bold text-white mb-0">-</span>
                      @else    
                      <span class="h2 font-weight-bold text-white mb-0">{{date('d-M-Y',strtotime($getLastTransaction->done_time))}}</span>
                      @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- <div class="col-xl-2 col-md-6">
        <div class="card card-stats bg-translucent-warning shadow-sm--hover shadow-none text-center">
            <div class="card-body">
                  <h5 class="card-title text-uppercase text-white mb-0">Total Transactions</h5>
                  <span class="h2 font-weight-bold text-white mb-0">23</span>
              </div>
        </div>
      </div>
          <div class="card shadow-sm">
            <div class="card-header">
              {{$user->name}}
            </div>
          </div> --}}
    </div>
@endsection