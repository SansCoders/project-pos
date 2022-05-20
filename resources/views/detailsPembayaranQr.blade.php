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
        
        @if (session()->get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ session()->get('error') }}</strong>
                    </div>
                @endif
                @if (session()->get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ session()->get('success') }}</strong>
                    </div>
                @endif
                @if ($message = Session::get('err'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button> 
                        <strong>{{ $message }}</strong>
                    </div>
        @endif
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
                            <button class="btn btn-lg btn-success" type="button" data-toggle="modal" data-target="#formUploadBukti">upload bukti pembayaran</button>
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

    <div class="modal fade" id="formUploadBukti" tabindex="-1" role="dialog" aria-labelledby="formUploadBuktiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="formUploadBuktiLabel">Upload Bukti Pembayaran</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('upload-bukti-pembayaran',$FakturDetails->order_id)}}" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex flex-wrap">
                        <div class="form-group">
                            <label>Bank</label>
                            <select class="form-control" name="bank_id" required>
                                @foreach (\App\BankInfo::getAllBankInfos() as $key => $bankInfo)
                                <option value="{{$bankInfo->id}}">{{$bankInfo->bank_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group ml-2">
                            <label>Bukti Pembayaran</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">upload</button>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th>No</th>
                            <th>Bukti Pembayaran</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                        @foreach (\App\BuktiTransfer::getBuktiTF($FakturDetails->id) as $index => $item)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>
                                <a href="{{asset($item->bukti_transfer_image_path)}}" target="_blank">lihat</a>
                            </td>
                            <td>{{$item->created_at}}</td>
                            <td>
                                <form action="{{route('delete-bukti-pembayaran',$FakturDetails->order_id)}}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input type="hidden" name="id"value="{{$item->id}}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">delete</button>
                                </form>
                        </td>
                        </tr>
                        @endforeach
                    </table>
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