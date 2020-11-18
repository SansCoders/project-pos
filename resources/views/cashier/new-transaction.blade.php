@extends('dashboard-layout.master')

@section('add-css')
    <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
    <style>
        *::-webkit-scrollbar {
        display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        * {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        }

        button.btn.btn-lg.btn-icon-only.cbtn.btn-default::after{
            content: "";
            display: block;
            position: absolute;
            width: 1em;
            height: 1em;
            top: 0;
            /* left: 50%; */
            transform: translate(-50%, -50%);
            background: red;
            border-radius: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5 mb-5">
        <div class="mb-4">
            <a href="{{ route('cashier.transaction') }}"><i class="fa fa-arrow-alt-circle-left"></i></a>
            <strong>Transaksi Barang</strong> 
        </div>
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
        <div class="row" id="listproducts">
            @include('another.cashier-productlist')
        </div>
    </div>

    <div class="modal fade" id="Modaladdtocart" tabindex="-1" role="dialog" aria-labelledby="addtocartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addtocartModalLabel">Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class="xspan">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="contentAddtoCart">
              
            </div>
          </div>
        </div>
      </div>

    <div class="cartCashier position-fixed" style="bottom: 10px; right: 20px;">
        <button class="btn btn-lg btn-icon-only btn-default cbtn" style="width: 50px; height: 50px" onclick="openCart()"><i class="ni ni-cart ci"></i></button>
    </div>
    <div class="popupCart position-fixed d-none" style="bottom: 80px; right: 20px; max-width: 500px;  width:90%">
        <div class="card shadow-sm" style="border: solid black">
            <div class="card-body content-popup" style="max-height: 50vw; overflow: scroll">
                <i class="fa fa-spinner fa-spin"></i>
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
    
    function openCart()
    {
        $('.cbtn').toggleClass('btn-default');
        $('.cbtn').toggleClass('btn-warning');
        $('.ci').toggleClass('ni-cart');
        $('.ci').toggleClass('ni-bold-up');
        $('.popupCart').toggleClass('d-none');
        $('.cbtn').attr('onclick','closeCart()');
        $.ajax({
            url : "/cashier/clt",
            method:"post",
            data : {_token:"{{ csrf_token() }}"},
            success: function(resp){
                $('.content-popup').html(resp);
            }
        });
    }
    function closeCart()
    {
        $('.cbtn').toggleClass('btn-default');
        $('.cbtn').toggleClass('btn-warning');
        $('.ci').toggleClass('ni-cart');
        $('.ci').toggleClass('ni-bold-up');
        $('.popupCart').toggleClass('d-none');
        $('.cbtn').attr('onclick','openCart()');
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