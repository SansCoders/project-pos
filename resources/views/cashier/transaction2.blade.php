@extends('dashboard-layout.master')

@section('add-css')

<style>
    #listproducts {
        flex: 100%;
        overflow-y: scroll;
        /* height: 100vh; */
        gap: 5px;
    }

    #listproducts>div {
        max-width: 300px;
    }

    #checkoutProducts {
        flex: 40%
    }

    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }


    @media (max-width: 800px) {
        #checkoutProducts {
            flex: 0;
            display: none;
        }

        #listproducts> {
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
    <div class="search-form mb-3 position-sticky" style="max-width: 350px; top:20px; z-index: 1">
        
        <div class="mb-2">
            <a href="#transactionPending" class="badge badge-warning">
                Transaksi Pending <span class="ml-3 badge badge-secondary">0</span>
            </a>
        </div>
        <!-- <form action="{{ route('cashier.newtransaction.search') }}" method="get"> -->
        <div class="form-group d-flex align-items-center">
            <input type="text" name="search" id="searchbrg" placeholder="cari barang" class="form-control">
            <!-- <button type="submit" class="btn btn-default btn-icon-only"><i class="fa fa-search"></i></button> -->
        </div>
        <span class="ml-5" id="lodingsearch"></span>
        <!-- </form> -->
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
        <div class="d-flex flex-wrap" style="" id="listproducts">
            @include('another.cashier-productlist2')
        </div>
        <div class="" style="" id="checkoutProducts">
            <div class="card bg-gradient-white border-default shadow-none" style="border: 1px solid black">
                <div class="card-body">
                    <h2 class="font-weight-bold text-center mb-3">
                        Keranjang
                    </h2>
                    <hr class="my-3">
                    <div class="" id="listCheckoutProducts">
                        <!-- <div class="d-flex align-items-center">
                            <div class="">lorem</div>
                            <div class="ml-auto">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-secondary btn-sm">-</button>
                                    <input type="number" style="max-width: 50px;" min="1" class="form-control" value="1" name="" id="">
                                    <button type="button" class="btn btn-secondary btn-sm">+</button>
                                </div>
                            </div>
                            <div class="ml-2">
                                10,000
                            </div>
                        </div> -->
                    </div>
                    <div class="font-weight-bold text-center justify-content-between d-flex mt-3">
                        <h2>Total</h2>
                        <h2 id="vTotalHarga">
                            <i class="fas fa-circle-notch fa-spin"></i>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="card bg-gradient-white border-default shadow-none">
                <div class="card-body">
                    <div class="font-weight-bold text-center justify-content-between d-flex">
                        <h4>Pembeli/Sales <span class="text-danger">*</span></h4>
                        <h2>
                            <!-- <i class="fas fa-circle-notch fa-spin"></i> -->
                        </h2>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="user_type" value="cns" required checked>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="ibuyer_name" placeholder="nama pembeli">
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-6">
                            <div class="form-group">
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="radio" name="user_type" value="cr" required>
                                        </div>
                                    </div>
                                    <select name="user_registered" id="" class="form-control">
                                        <option disabled selected>Pelanggan Terdaftar</option>
                                        @php
                                        $UserList = App\User::where('status',1)->get();
                                        @endphp
                                        @if (count($UserList) > 0)
                                        @foreach ($UserList as $customer)
                                        <option value="{{ $customer->id }}"><strong>{{$customer->name}}</strong> @isset($customer->phone)({{$customer->phone}})@endisset</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            {{-- @if ($data->count() > 0) --}}
            <button id="konfir_checkout" class="btn btn-block btn-primary disabled" data-toggle="modal" data-target="#confirmCheckout">KONFIRMASI & CETAK FAKTUR</button>
            {{-- @endif --}}
        </div>
    </div>
    <div class="mt-5" id="transactionPending">
        <div class="card shadow-sm">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
                <b>Transactions Pending</b>
                <div class="form-search">
                    <form class="form-inline" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="search">
                            <div class="input-group-append">
                              <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>no</th>
                            <th>order id</th>
                            <th>sales</th>
                            <th>tanggal</th>
                            <th>status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @isset($cari)
                            @if (count($transactions) < 1)
                                <tr>
                                    <td colspan="6" class="text-center">tidak ditemukan hasil <b>"{{ $cari }}"</b></td>
                                </tr>
                            @endif
                            @foreach ($transactions as $index => $t)
                                <tr>
                                    <td>#</td>
                                    <td class="d-none">{{ $t->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="">#{{ $t->transaction_id }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="{{ $t->buyer->name }}">
                                            <img alt="Image placeholder" src="../assets/img/theme/team-1.jpg">
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span data-toggle="tooltip" data-original-title="{{ strftime('%H:%M, %d %B %Y', strtotime( $t->created_at)) }}">
                                                {{Carbon\Carbon::parse($t->created_at)->diffForHumans()}}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-dot mr-4">
                                        <i class="bg-warning"></i>
                                        <span class="status">pending</span>
                                        </span>
                                    </td>
                                    <td>
                                    <a href="{{ route('cashier.check.checkout',$t->transaction_id) }}" class="btn btn-success btn-sm process">proses</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        @php
                            $transactions_pending_count = 0;
                        @endphp
                        @foreach ($transactions as $index => $t)
                            @if ($t->is_done == 0 && $t->status == 'pending')   
                            <tr>
                                <td>#</td>
                                <td class="d-none">{{ $t->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="">#{{ $t->transaction_id }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="#" class="avatar avatar-sm rounded-circle" data-toggle="tooltip" data-original-title="{{ $t->buyer->name }}">
                                        <img alt="Image placeholder" src="../assets/img/theme/team-1.jpg">
                                      </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span data-toggle="tooltip" data-original-title="{{ strftime('%H:%M, %d %B %Y', strtotime( $t->created_at)) }}">
                                            {{Carbon\Carbon::parse($t->created_at)->diffForHumans()}}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-dot mr-4">
                                      <i class="bg-warning"></i>
                                      <span class="status">pending</span>
                                    </span>
                                </td>
                                <td>
                                <a href="{{ route('cashier.check.checkout',$t->transaction_id) }}" class="btn btn-success btn-sm process">proses</a>
                                </td>
                            </tr>
                            @php
                                $transactions_pending_count += 1;
                            @endphp
                            @endif
                            
                        @endforeach
                        
                        @if ($transactions_pending_count == 0)
                            <tr>
                                <td colspan="6" class="text-center text-muted">semua transaksi sudah diproses</td>
                            </tr>
                        @endif
                        @endisset
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmCheckout" tabindex="-1" role="dialog" aria-labelledby="confirmCheckoutLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCheckoutLabel">Konfirmasi Checkout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contentConfirmCheckout">
                <div class="alert alert-warning mb-2"><i class="fa fa-exclamation-triangle"></i> pastikan nama pembeli sudah diisi</div>
                <i class="fa fa-spinner fa-spin"></i> Mohon Tunggu
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script>
    var loadingCompt = '<i class="fas fa-circle-notch fa-spin"></i>';
    $(document).ready(function() {
        showCheckoutCartProducts();
        showTotalHargaCart();
    });

    function qtyChange() {
        $('.min-qty').click(function(e) {
            e.preventDefault();
            var cartid = $(this).data('cartid');
            var vQty = parseInt($('input.cvqty[data-cartid=' + cartid + ']').val());
            var fvQty = vQty - 1;
            if(validationStock(fvQty)){
                $('input.cvqty[data-cartid=' + cartid + ']').val(fvQty);
                ajaxUCart(cartid, fvQty);
            }
        });
        $('.plus-qty').click(function(e) {
            e.preventDefault();
            var cartid = $(this).data('cartid');
            var vQty = parseInt($('input.cvqty[data-cartid=' + cartid + ']').val());
            var fvQty = vQty + 1;
            if(validationStock(fvQty)){
                $('input.cvqty[data-cartid=' + cartid + ']').val(fvQty);
                ajaxUCart(cartid, fvQty);
            }

        });
        function validationStock(qty,cartid = null){
            if(qty > 0){
                return true;
            }
            return false;
        }
        function ajaxUCart(cartid, qty) {
            $.ajax({
                url: "/cashier/updateCart",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    cartid: cartid,
                    valbuy: qty
                },
                beforeSend: function() {
                    $('.tothrg-' + cartid).html(loadingCompt);
                },
                success: function(resp) {
                    showCheckoutCartProducts();
                    showTotalHargaCart();
                }
            });
        }
        $('.delete-item').click(function(e) {
            e.preventDefault();
            var cartid = $(this).data('cartid');
            $.ajax({
                url: "/cashier/deleteCart",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    cartid: cartid
                },
                success: function(resp) {
                    console.log();
                    showCheckoutCartProducts();
                    showTotalHargaCart();
                }
            });
        });
        $(".cvqty").on("input", function() {
            var cartid = $(this).data('cartid');
            var fvQty = parseInt($(this).val());
            if(validationStock(fvQty)){
                ajaxUCart(cartid, fvQty);
            }
        });
    }
    function toggleBtnKonfir(type = null) {
        if(type == "active"){
            $('#konfir_checkout').removeClass('disabled');
        }else{
            $('#konfir_checkout').addClass('disabled')
        }
    }
    $("input[name=ibuyer_name]").on("input", function() {
        var isi = $(this).val();
        if(isi.length > 0){
            toggleBtnKonfir("active");
        }else{
            toggleBtnKonfir();
        }
    });
    $('.seeProduct').click(function(e) {
        e.preventDefault();
        var idp = $(this).data('idp');
        $.ajax({
            url: "/cashier/seeproduct",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                'idp': idp
            },
            success: function(resp) {
                $('#contentAddtoCart').html(resp);
            }
        });
    });

    function cartDetails() {
        $.ajax({
            url: "/cashier/clt",
            method: "post",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(resp) {
                $('.content-popup').html(resp);
            }
        });
    }


    function showCheckoutCartProducts() {
        $.ajax({
            url: "/cashier/cartproductscheckout",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(resp) {
                $('#listCheckoutProducts').html(resp);
            }
        }).done(qtyChange);
    }

    // $('input[name=search]').keyup(function() {
    //     var cari = $(this).val();
    //     console.log(cari);
    //     $.ajax({
    //         url: "/cashier/search-brg",
    //         method: "post",
    //         data: {
    //             "_token": "{{ csrf_token() }}",
    //             cari: cari
    //         },
    //         beforeSend: function() {
    //             $('#lodingsearch').html(loadingCompt);
    //         },
    //         success: function(resp) {
    //             $('#lodingsearch').html('<span>hasil untuk </span><b>"' + cari + '"</b>');
    //             $('#listproducts').html(resp);
    //         }
    //     });
    // });
    function ajaxSearch(params) {
        $.ajax({
            url: "/cashier/search-brg",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                cari: params
            },
            beforeSend: function() {
                $('#lodingsearch').html(loadingCompt);
            },
            success: function(resp) {
                $('#lodingsearch').html('<span>hasil untuk </span><b>"' + params + '"</b>');
                $('#listproducts').html(resp);
            }
        });
    }
    $('input[name=search]').keyup(function() {
        window.timer = setTimeout(function() { // setting the delay for each keypress
            var cari = $('input[name=search]').val();
            ajaxSearch(cari);
        }, 1000);
    }).keydown(function() {
        clearTimeout(window.timer);
    });

    function showTotalHargaCart() {
        $.ajax({
            url: "/cashier/get/totalharga",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
            },
            beforeSend: function() {
                $('#vTotalHarga').html(loadingCompt);
            },
            success: function(resp) {
                $('#vTotalHarga').text(resp);
            }
        });
    }


    $('.addKeranjang').click(function(e) {
        e.preventDefault();
        var idp = $(this).data('idp');
        $.ajax({
            url: "/cashier/addToCart",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "idp": idp,
                valbuy: 1
            },
            success: function(resp) {
                console.log(resp);
            }
        });
        showCheckoutCartProducts();
        showTotalHargaCart();
    });

    function addKeranjang(idp) {
        $.ajax({
            url: "/cashier/addToCart",
            method: "post",
            data: {
                "_token": "{{ csrf_token() }}",
                "idp": idp,
                valbuy: 1
            },
            success: function(resp) {
                console.log(resp);
            }
        });
        showCheckoutCartProducts();
        showTotalHargaCart();
    }
    
    $('#konfir_checkout').click(function(e) {
        e.preventDefault();
        if(!$(this).hasClass('disabled')){
            $.ajax({
                url: "/cashier/checkout-check",
                method: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function() {
                    $('#contentConfirmCheckout').html(loadingCompt);
                },
                success: function(resp) {
                    $('#contentConfirmCheckout').html(resp);
                    $('#buyername').text($('input[name=ibuyer_name]').val());
                    $('#input_buyername').val($('input[name=ibuyer_name]').val());
                }
            });
        }
    });

    function loadMoreProduct(page) {
        $.ajax({
                url: '?page=' + page,
                type: 'get',
                // beforeSend: function()
                // {
                //     $("#load-more-product").show();
                // }
            })
            .done(function(product) {
                if (product.html == " ") {
                    console.log('asdasd');
                    return;
                }
                // $("#load-more-product").hide();
                $('#listproducts').append(product.html);
            })
            .fail(function(jqXHR, ajaxOptions, throwError) {
                // $("#load-more-product").hide();
            });
    }

    var page = 1;
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            loadMoreProduct(page);
        }
    });
</script>
@endpush