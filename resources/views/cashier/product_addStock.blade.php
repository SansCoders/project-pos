@extends('dashboard-layout.master')

@section('add-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/animate.css/animate.min.css') }}">
@endsection
@section('content')
    <div class="container-fluid mt-5 mb-5">
        <div class="row">
            <div class="col-md-1">
                <a href="{{route('stock.add')}}" class="btn btn-icon"><i class="fa fa-arrow-alt-circle-left"></i></a>
            </div>
            <div class="col-md-11">
                <div class="alert alert-warning" role="alert">
                    <span class="alert-icon"><i class="fa fa-exclamation-triangle"></i></span>
                    <span class="alert-text"><strong>Perhatikan !</strong> halaman ini hanya bisa melakukan tambah persediaan produk atau barang masuk</span>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-xl-12">
                <div class="card shadow-sm--hover shadow-none">
                    <div class="card-body row">
                        <div class="col-lg-4 col-sm-12 col-md-4 text-center mb-2">
                            <img src="{{ asset($product->img) }}" alt="" style="max-width: 200px;border-radius:10px">
                        </div>
                        <div class="col-lg-8 col-md-8 text-center align-self-center" >
                            <h1>{{ $product->nama_product }}</h1>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card shadow-none shadow-sm--hover">
                    <div class="card-body text-center d-flex flex-column align-items-center">
                        <span class="h3">Persediaan Sekarang</span>
                        <span class="h1 mt-4 font-weight-bold">{{ $product->stocks->stock }} {{ $product->unit->unit }}</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6">
                <div class="card shadow-none shadow-sm--hover">
                    <div class="card-body text-center d-flex flex-column align-items-center">
                        <span class="h3">Persediaan Masuk</span>
                        <form id="formStockIn" action="{{route('stock.stockIn.process',$product->id)}}" method="POST">
                            @csrf
                            @method('put')
                            <input type="number" name="stock_in" class="mt-4 form-control form-control-alternative" required />
                        </form>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                    <span>{{ $error }}</span>
                                    @endforeach
                            </div><br />
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <button id="submitAddstock" class="btn btn-block btn-success">tambah stock</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{asset('assets/vendor/bootstrap-notify/bootstrap-notify.min.js')}}"></script>  
    
<script>
    $('#submitAddstock').click(function(){
        $('#formStockIn').submit();
    });
</script>

@if(session()->get('message'))
<script>
    $.notify({
        icon: 'fa fa-check',
	    message: '{{ session()->get('message') }}'
        },{
            element: 'body',
            position: null,
            type: "success",
            allow_dismiss: true,
            newest_on_top: false,
            placement: {
                from: "top",
                align: "right"
            },
            z_index: 1031,
            delay: 5000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            icon_type: 'class',
            template: '<div data-notify="container" class="d-flex col-xs-11 col-sm-3 alert alert-{0}" role="alert" style="width:calc(100% - 30px);">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon" class="mr-2" style="place-self: center;"></span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
            '</div>' 
        });
</script>
@endif
@endpush