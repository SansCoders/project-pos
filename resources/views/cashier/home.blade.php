@extends('dashboard-layout.master')

@section('content')

    <div class="header pb-6 d-flex align-items-center" style="min-height: 150px; background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-primary opacity-8"></span>
    </div>
<div class="container-fluid mt--8">
    <div class="row">
        @if ($transactionPending->count() > 0)
        <div class="col-lg-12 text-center">
            <div class="card shadow-none bg-default">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="h3 text-white">{{$transactionPending->count()}} transaksi menunggu diproses</span>
                    </div>
                    <a href="{{route('cashier.transaction')}}" class="btn btn-sm btn-neutral btn-block">cek</a>
                </div>
            </div>
        </div>
        @else    
            <div class="col-lg-12 text-center">
                <div class="card shadow-none bg-success">
                    <div class="card-body d-flex align-items-center justify-content-center">
                        <i class="fa fa-check-circle text-white mr-2"></i>
                        <span class="h3 text-white mb-0">semua transaksi sudah diproses</span>
                        {{-- <a href="#" class="btn btn-sm btn-neutral btn-block">cek</a> --}}
                    </div>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card shadow-none">
                <div class="card-body">
                    <h5 class="card-title">Pencarian produk</h5>
                    <input id="inputkode" type="text" class="form-control form-control-alternative" placeholder="Masukkan kode produk">
                    <br/>
                    <div class="text-right">
                        <button class="btn btn-icon btn-primary" type="button" data-toggle="modal" data-target="#listproductsModal">
	                        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            <span class="btn-inner--text">Cari Produk</span>
                        </button>
                    </div>
                    
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Produk</h5>
                    <div class="product-details"></div>
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

@push('scripts')
    <script>
        $('#inputkode').keyup(function(){
            var v = $(this).val();
            if(v.length >= 3){
                $('.product-details').html(v);
            }
        });
    </script>
@endpush