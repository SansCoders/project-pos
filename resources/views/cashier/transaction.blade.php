@extends('dashboard-layout.master')

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 150px; background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-primary opacity-8"></span>
</div>

<div class="container-fluid mt--6">
    <div class="card shadow-sm">
        <div class="card-header border-0">
            <b>Transactions Pending</b>
        </div>
        <div class="table-responsive">
            <table class="table table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>no</th>
                        <th>order id</th>
                        <th>sales</th>
                        <th>tanggal</th>
                        <th>status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @php
                        $transactions_pending_count = 0;
                    @endphp
                    @foreach ($transactions as $index => $t)
                        @if ($t->is_done == 0)   
                        <tr>
                            <td>#</td>
                            <td class="d-none">{{ $t->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="">#{{ $t->transaction_id }}</span>
                                </div>
                            </td>
                            <td>
                                <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="{{ $t->buyer->name }}">
                                    <img alt="Image placeholder" src="../assets/img/theme/team-1.jpg">
                                  </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span data-toggle="tooltip" data-original-title="{{ strftime('%H:%M, %d %B %Y', strtotime( $t->created_at)) }}">
                                        {{Carbon\Carbon::parse($t->created_at)->diffForHumans()}}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-dot mr-4">
                                  <i class="bg-warning"></i>
                                  <span class="status">pending</span>
                                </span>
                            </td>
                            <td>
                            <a href="{{ route('cashier.check.checkout',$t->transaction_id) }}" class="btn btn-success btn-sm process">proses</a>
                            </td>
                        </tr>
                        @php
                            $transactions_pending_count += 1;
                        @endphp
                        @endif
                        
                    @endforeach
                    
                    @if ($transactions_pending_count == 0)
                        <tr>
                            <td colspan="6" class="text-center text-muted">semua transaksi sudah diproses</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection