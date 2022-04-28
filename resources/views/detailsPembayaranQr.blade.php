@extends('dashboard-layout.master')
@include('dashboard-layout.footer')
@php
    $cart = \App\Keranjang::getCart();
    $cekTransactions = \App\Receipts_Transaction::getTransaction();
@endphp
@section('content')

    <div class="container-fluid mt-5 pb-5">
        <div class="d-flex mb-4 align-items-center">
            <a href="#" class="btn btn-neutral"><i class="fa fa-arrow-alt-circle-left"></i></a>
            <h2 class="ml-2 mb-0"><i class="fa fa-receipt"></i> Order Details</h2>
        </div>
        <div class="card">
            <div class="card-body" id="content-Receipts_d" data-order-id="{{$FakturDetails->id}}">
                <div id="loading">
                    <i id="loading-icon" class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="alert alert-secondary"><i class="fa fa-info"></i> silahkan lakukan pembayaran dari metode pembayaran di bawah ini dan upload bukti jika sudah melakukan pembayaran</div>
                <div class="">
                    <div class="d-flex justify-content-between flex-wrap-reverse">
                        <div class="">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                @foreach (\App\BankInfo::getAllBankInfos() as $key => $bankInfo)
                                <li class="nav-item" role="methodPayment">
                                  <a class="nav-link {{ ($key == 0) ? 'active': '' }}" id="pills-bank-info-{{$key}}-tab" data-toggle="pill" href="#pills-bank-info-{{$key}}" role="tab" aria-controls="pills-bank-info-{{$key}}" aria-selected="true">{{$bankInfo->bank_name}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="">
                            <button class="btn btn-lg btn-success">upload bukti pembayaran</button>
                        </div>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        @foreach (\App\BankInfo::getAllBankInfos() as $key => $bankInfo)
                        <div class="tab-pane fade {{ ($key == 0) ? 'show active': '' }}" id="pills-bank-info-{{$key}}" role="tabpanel" aria-labelledby="pills-bank-info-{{$key}}-tab">
                            {{-- {{$bankInfo}} --}}
                            <div class="row">
                                <div class="col-md-5">
                                    <img src="{{asset($bankInfo->qr_code)}}" alt="">
                                </div>
                                <div class="col-md-7">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-secondary">
                                            <h4 class="card-title m-0">Details</h4>
                                        </div>
                                        <div class="card-body">
                                            <span>Bank : {{$bankInfo->bank_name}}</span><br>
                                            <span>No. Rekening : <b>{{$bankInfo->rekening_number}}</b></span><br>
                                            <span>a.n. : <b>{{$bankInfo->rekening_owner_name}}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
            $(document).ready(function(){
                var idproduct = $('#content-Receipts_d').data('order-id');
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