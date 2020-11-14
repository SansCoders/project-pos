@extends('dashboard-layout.master')

@section('content')
<div class="container-fluid mt-6">
    <div class="card shadow-sm">
        <div class="card-header border-0 d-flex justify-content-between align-items-center">
            <b>Transactions</b>
            <div class="form-search">
                <form action="" method="GET">
                    @csrf
                    <input type="text" name="search" class="form-control" placeholder="search">
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>no</th>
                        <th>no faktur</th>
                        <th>order id</th>
                        <th>sales</th>
                        <th>tanggal</th>
                        <th>status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="list">
                    @php
                        $transaction_count = 0;
                    @endphp
                    {{-- fakturs --}}
                    {{-- transaction --}}
                    @foreach ($transaction as $index => $t)
                        <tr>
                            <td>{{$transaction_count+1}}</td>
                            <td class="d-none">{{ $t->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="">{{sprintf("%08d", $t->facktur->faktur_number)}}</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="">#{{ $t->transaction_id }}</span>
                                </div>
                            </td>
                            <td>
                                <strong class="text-dark">{{ $t->user_name }}</strong>
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
                                @if ($t->status == 'pending')
                                    <i class="bg-warning"></i>
                                    <span class="status">pending</span>
                                @elseif ($t->status == 'confirmed')
                                    <i class="bg-success"></i>
                                    <span class="status">done</span>
                                @else    
                                    <i class="bg-dark"></i>
                                    <span class="status">canceled</span>
                                @endif
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="group btn">
                                    <button type="button" class="btn btn-info pl-2 pr-2 product_details" data-toggle="modal" data-idpro="{{$t->id}}" data-target="#detailsProduct">Details</button>
                                    <form action="{{ route('cashier.downloadFaktur') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="orderId" value="{{ $t->id }}" required>
                                        <button type="submit" class="btn btn-success btn-icon-only pl-2 pr-2"><i class="fa fa-download"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @php
                            $transaction_count += 1;
                        @endphp
                        
                    @endforeach
                    
                    @if ($transaction_count == 0)
                        <tr>
                            <td colspan="6" class="text-center text-muted">belum ada aktivitas</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="detailsProduct" tabindex="-1" role="dialog" aria-labelledby="detailsProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailsProductLabel">Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="content-Receipts_d">
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
            $('.product_details').click(function(){
                var idproduct = $(this).data("idpro");
                $.ajax({
                    url : "/list-orders/details",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idReceipts" : idproduct},
                    success: function(resp){
                        $('#content-Receipts_d').html(resp);
                    }
                });
            });
    </script>
@endpush