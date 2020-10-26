@extends('dashboard-layout.master')
@section('header-content')
<div class="header pb-6">
    {{-- <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <h6 class="h2 text-dark d-inline-block mb-0">Default</h6>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                <li class="breadcrumb-item active" aria-current="page">Default</li>
              </ol>
            </nav>
          </div>
          <div class="col-lg-6 col-5 text-right">
            <a href="#" class="btn btn-sm btn-neutral">New</a>
            <a href="#" class="btn btn-sm btn-neutral">Filters</a>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mr-2 ml-2">
        <div class="col-8">
            <div class="row align-items-center justify-content-start">
                <h3 class="col-12 mb-3">This Week</h3>
                <a href="#" class="mr-4 card-lift--hover">
                    <div class="card shadow-sm text-center col-xs-4 ">
                        <img class="card-img-top" src="https://2img.net/u/3613/18/20/56/avatars/512-17.gif" alt="Card image cap">
                        <div class="card-body text-dark">
                            { Nama Produk }
                        </div>
                    </div>
                </a>
                <a href="#" class="mr-4 card-lift--hover">
                    <div class="card shadow-sm text-center col-xs-4 ">
                        <img class="card-img-top" src="https://2img.net/u/3613/18/20/56/avatars/512-17.gif" alt="Card image cap">
                        <div class="card-body text-dark">
                            { Nama Produk }
                        </div>
                    </div>
                </a>
            </div>
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        asdads
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection