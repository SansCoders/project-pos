@extends('dashboard-layout.master')
@section('content')
    <div class="container-fluid mt-5  h-100vh">
        <div class="container">
            <div class="d-flex mb-4 align-items-center">
                <a href="{{ url()->previous() }}" class="btn btn-neutral"><i class="fa fa-arrow-alt-circle-left"></i></a>
                <h2 class="ml-2 mb-0"><i class="fa fa-receipt"></i> Order History</h2>
            </div>
            <div class="row">
                <div class="card col-lg-12 p-0 shadow-none">
                    <div class="card-header border-0 d-flex justify-content-between align-items-center">
                        <span>List Transactions</span>
                        <div class="">
                            <form method="GET">
                                <input type="text" name="search" class="form-control form-control-alternative" value="{{request()->search}}" placeholder="Cari">
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>no</th>
                                    <th>Order Id</th>
                                    <th>Order Time</th>
                                    <th>Done Time</th>
                                    <th>Status</th>
                                    <th class="text-center">action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @isset($cari)
                                @php
                                    $i = 1;
                                @endphp
                                @if ($searchOrder->count() < 1)
                                    <tr>
                                    <td colspan="6" class="text-center">tidak ditemukan pencarian <b>"{{ $cari }}"</b></td>
                                    </tr>
                                @endif
                                @foreach ($searchOrder as $index => $order)
                                    <tr>
                                        <td>
                                            {{$i++}}
                                        </td>
                                        <td>
                                            #{{$order->transaction_id}}
                                        </td>
                                        <td>
                                            {{strftime('%H:%M:%S ,%d %B %Y',strtotime($order->created_at))}}
                                        </td>
                                        
                                        @if ($order->status == 'pending')
                                            <td class="text-center" colspan="2">
                                                <span class="badge badge-dot">
                                                    <i class="bg-warning"></i>
                                                    <span class="status">pending</span>
                                                </span>
                                            </td>
                                        @elseif ($order->status == 'confirmed')
                                            <td>
                                                {{strftime('%H:%M:%S ,%d %B %Y',strtotime($order->done_time))}}
                                            </td>
                                            <td>
                                                <span class="badge badge-dot">
                                                    <i class="bg-success"></i>
                                                    <span class="status">done</span>
                                                </span>
                                            </td>
                                        @else    
                                            <td class="text-center" colspan="2">
                                                <span class="badge badge-dot">
                                                    <i class="bg-dark"></i>
                                                    <span class="status">canceled</span>
                                                </span>
                                            </td>
                                        @endif
                                        <td class="text-center pl-0 pr-0">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                @if ($order->status == 'pending')
                                                <a href="{{route('pembayaran.details',$order->transaction_id)}}" class="btn btn-info pl-2 pr-2">upload bukti pembayaran</a>
                                                @else    
                                                <button type="button" class="btn btn-info pl-2 pr-2 product_details" data-toggle="modal" data-idpro="{{$order->id}}" data-target="#detailsProduct">Details</button>
                                                @endif
                                                <form action="/invoice" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderId" value="{{ $order->id }}" required>
                                                    <button type="submit" class="btn btn-success btn-icon-only pl-2 pr-2"><i class="fa fa-download"></i></button>
                                                </form>
                                              </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @else    
                                @if ($allOrders->count() < 1)
                                    <tr>
                                        <td colspan="6">anda belum ada transaksi</td>
                                    </tr>
                                @endif
                                @foreach ($allOrders as $index => $order)
                                    <tr>
                                        <td>
                                            {{$allOrders->firstitem() + $index}}
                                        </td>
                                        <td>
                                            #{{$order->transaction_id}}
                                        </td>
                                        <td>
                                            {{strftime('%H:%M:%S ,%d %B %Y',strtotime($order->created_at))}}
                                        </td>
                                        
                                        @if ($order->status == 'pending')
                                            <td class="text-center" colspan="2">
                                                <span class="badge badge-dot">
                                                    <i class="bg-warning"></i>
                                                    <span class="status">pending</span>
                                                </span>
                                            </td>
                                        @elseif ($order->status == 'confirmed')
                                            <td>
                                                {{strftime('%H:%M:%S ,%d %B %Y',strtotime($order->done_time))}}
                                            </td>
                                            <td>
                                                <span class="badge badge-dot">
                                                    <i class="bg-success"></i>
                                                    <span class="status">done</span>
                                                </span>
                                            </td>
                                        @else    
                                            <td class="text-center" colspan="2">
                                                <span class="badge badge-dot">
                                                    <i class="bg-dark"></i>
                                                    <span class="status">canceled</span>
                                                </span>
                                            </td>
                                        @endif
                                        <td class="text-center pl-0 pr-0">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                                @if ($order->status == 'pending')
                                                <a href="{{route('pembayaran.details',$order->transaction_id)}}" class="btn btn-info pl-2 pr-2">upload bukti pembayaran</a>
                                                @else    
                                                <button type="button" class="btn btn-info pl-2 pr-2 product_details" data-toggle="modal" data-idpro="{{$order->id}}" data-target="#detailsProduct">Details</button>
                                                @endif
                                                <form action="/invoice" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="orderId" value="{{ $order->id }}" required>
                                                    <button type="submit" class="btn btn-success btn-icon-only pl-2 pr-2"><i class="fa fa-download"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @isset($cari)
                        @else
                               {{$allOrders->links()}}                     
                        @endisset
                    </div>
                </div>
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
                    url : "/my-orders/details",
                    method:"post",
                    data : {"_token":"{{ csrf_token() }}","idReceipts" : idproduct},
                    success: function(resp){
                        $('#content-Receipts_d').html(resp);
                    }
                });
            });
    </script>
@endpush