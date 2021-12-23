@extends('dashboard-layout.master')

@section('add-css')
    
    <style>
        #listproducts{
            flex: 60%;
            overflow-y: scroll; 
            height:600px;
        }
        #listproducts > div{
            max-width: 50%;
            flex: 0 0 50%;
        }

        #checkoutProducts {
            flex: 40%
        }
        

        @media (max-width: 800px) {
            #checkoutProducts {
                flex: 0;
                display: none;
            }
            #listproducts > {
                flex: 100%;
            }
        }
    </style>
@endsection

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 150px; background-size: cover; background-position: center top;">
    {{-- <span class="mask bg-primary opacity-8"></span> --}}
</div>

<div class="container-fluid mt--6">
    @if(session()->get('success'))
    <div class="alert alert-default d-flex align-items-center">
        <strong class="text-yellow">{{ session()->get('success') }}</strong>
        <div class="ml-auto">
            @if (session()->get('id_t'))
                <small>
                    <a href="{{route('cashier.previewFaktur',session()->get('id_t'))}}" target="_blank" class="btn btn-success text-white">lihat faktur</a>
                </small>
                <script>
                    window.open("{{route('cashier.previewFaktur',session()->get('id_t'))}}");
                </script>
            @endif
        </div>
    </div>
    @endif
    
    @if (session()->get('error'))
        <div class="alert alert-danger d-flex align-items-center">
            <strong class="text-white">{{ session()->get('error') }}</strong>
        </div>
    @endif
    <div class="search-form mb-3 position-sticky" style="max-width: 200px; top:20px; z-index: 1">
        <form action="{{ route('cashier.newtransaction.search') }}" method="get">
            <div class="form-group d-flex align-items-center">
                <input type="text" name="search" placeholder="search" class="form-control">
                <button type="submit" class="btn btn-default btn-icon-only"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
    @if($errors->has('buyer_name'))
        <div class="alert alert-warning" role="alert">
            <strong>Warning!</strong> {{ $errors->first('buyer_name') }}
        </div>
    @endif

    @if(session()->get('error'))
        <div class="alert alert-danger" role="alert">
            <strong>Warning!</strong> {{ session()->get('error') }}
        </div>
    @endif
    <div class="d-flex flex-row" style="gap: 20px">
        <div class="row" style="" id="listproducts">
            @include('another.cashier-productlist2')
        </div>
        <div class="" style="" id="checkoutProducts">
            <div class="card bg-gradient-white border-default shadow-none" style="border: 1px solid black">
                <div class="card-body">
                    <h2 class="font-weight-bold text-center mb-3">
                        Keranjang
                    </h2>
                    <hr class="my-3">
                    <div class="font-weight-bold text-center justify-content-between d-flex">
                        <h2>Total</h2>
                        <h2>
                            {{-- @currency($priceTotal) --}}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="card bg-gradient-white border-default shadow-none">
                <div class="card-body">
                    <div class="font-weight-bold text-center justify-content-between d-flex">
                        <h4>Pembeli/Sales</h4>
                        <h2>
                            {{-- @currency($priceTotal) --}}
                        </h2>
                    </div>
                </div>
            </div>
            {{-- @if ($data->count() > 0) --}}
                <button class="btn btn-block btn-primary" data-toggle="modal" data-target="#confirmCheckout">KONFIRMASI & CETAK FAKTUR</button>
            {{-- @endif --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script>
    $('.seeProduct').click(function(e){
        e.preventDefault();
        var idp = $(this).data('idp');
        $.ajax({
            url : "/cashier/seeproduct",
            method:"post",
            data : {"_token":"{{ csrf_token() }}",'idp' : idp},
            success: function(resp){
                $('#contentAddtoCart').html(resp);
            }
        });
    });

    function cartDetails()
    {
        $.ajax({
            url : "/cashier/clt",
            method:"post",
            data : {_token:"{{ csrf_token() }}"},
            success: function(resp){
                $('.content-popup').html(resp);
            }
        });
    }

    function loadMoreProduct(page)
    {
        $.ajax({
            url: '?page=' + page,
            type: 'get',
            // beforeSend: function()
            // {
            //     $("#load-more-product").show();
            // }
        })
        .done(function(product){
            if(product.html == " "){
                console.log('asdasd');
                return;
            }
            // $("#load-more-product").hide();
            $('#listproducts').append(product.html);
        })
        .fail(function(jqXHR, ajaxOptions, throwError){
            // $("#load-more-product").hide();
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