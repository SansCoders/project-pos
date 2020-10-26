@extends('dashboard-layout.master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard Kasir') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Auth::guard('admin')->check())
                        Hello {{Auth::guard('admin')->user()->name}}
                    @elseif(Auth::guard('cashier')->check())
                        Hello {{Auth::guard('cashier')->user()->name}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection