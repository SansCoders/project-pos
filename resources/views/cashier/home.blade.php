@extends('dashboard-layout.master')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
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