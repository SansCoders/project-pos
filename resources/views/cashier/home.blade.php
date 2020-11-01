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
                    <input type="text" class="form-control form-control-alternative" placeholder="Masukkan kode produk">
                    <br/>
                    <div class="text-right">
                        <button class="btn btn-icon btn-primary" type="button">
	                        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
                            <span class="btn-inner--text">Cari Produk</span>
                        </button>
                    </div>
                    
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Detail Produk</h5>
                    
                </div>
            </div>
               
        </div>
    </div>
</div>
@endsection