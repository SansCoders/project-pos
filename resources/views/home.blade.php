@extends('dashboard-layout.master')
@include('dashboard-layout.footer')
@section('content')
<nav class="header bg-gradient-gray">
    <div class="container">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <form action="{{ route('search.product') }}" method="POST" class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                        @csrf
                        <div class="form-group mb-0">
                          <div class="input-group input-group-alternative input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input class="form-control" name="cari" placeholder="Cari Produk" type="text">
                          </div>
                        </div>
                        <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                    </form>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{route('checkout')}}" role="button" class="btn btn-icon btn-primary">
                        <span class="btn-inner--icon"><i class="fa fa-cash-register"></i></span>
                        @if ($cart->count() > 0)
                        <span class="btn-inner--text">Checkout ({{$cart->count()}}) </span>
                        @else
                        <span class="btn-inner--text">Checkout</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-5">
    @if ($cekTransactions->count() > 0)
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('my-orders') }}">
                    <div class="alert alert-default d-flex align-items-center" role="alert">
                        <div class="alert-desc">
                            <strong>{{$cekTransactions->count()}} Pesanan</strong> menunggu diproses 
                        </div>
                        <span class="ml-auto">Lihat</span>
                    </div>
                </a>
            </div>
        </div>
    @endif
    <div id="listproducts" class="list-group flex-wrap flex-row align-items-center">
        @include('another.home-productslist')
        {{-- @if(session()->get('notfound'))
        <div class="alert alert-warning">
            {{$notfound}}
        </div>
        @endif --}}
    </div>
    <div id="load-more-product" style="text-align: center; display: none">
        <i class="fa fa-circle-notch fa-spin"></i>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function loadMoreProduct(page)
    {
        $.ajax({
            url: '?page=' + page,
            type: 'get',
            beforeSend: function()
            {
                $("#load-more-product").show();
            }
        })
        .done(function(product){
            var data = product.html;
            if(data == ""){
                $("#load-more-product").hide();
                
                return;
            }
            console.log(data);
            $("#load-more-product").hide();
            $('#listproducts').append(data);
        })
        .fail(function(jqXHR, ajaxOptions, throwError){
            $("#load-more-product").hide();
                alert('asd');
            // break;
            return false;
        });
    }

    var page = 1;
    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() >= $(document).height()){
            page++;
            loadMoreProduct(page);
        }
    });
</script>
@endpush