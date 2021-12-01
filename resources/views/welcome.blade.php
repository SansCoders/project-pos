<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="POS APP">
    <title>
        @php
            $g_products = App\Product::where('product_status','show')->paginate(12);
            $count_Allproducts = App\Product::where('product_status','show')->count();
            $g_categ = App\CategoryProduct::where('status',1)->paginate(10);
            $constCompany = App\AboutUs::first();
            if($constCompany == null) {
                $constNamaCompany = "App POS";
            }else{
                $constNamaCompany = $constCompany->name;
            }
        @endphp
        {{$constNamaCompany}}</title>
    
        <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <link rel="stylesheet" href="{{ asset('assets/vendor/nucleo/css/nucleo.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('assets/css/argon.css?v=1.2.0') }}" type="text/css">
        <style>
            .bodyClick{
                background: dimgray;
                opacity: 20%;
            }
        </style>
</head>
    <body>
        <div class="main-content" id="panel">    
            <nav class="navbar navbar-horizontal navbar-expand-lg navbar-dark bg-primary">
                <div class="container">
                    <a class="navbar-brand font-weight-bolder" href="{{url('/')}}">{{$constNamaCompany}}</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbar-default">
                        <div class="navbar-collapse-header">
                            <div class="row">
                                <div class="col-6 collapse-brand">
                                    <a href="javascript:void(0)">
                                        @if ($constCompany->img_company == null)
                                        <img src="#">
                                         @else
                                         <img src="{{asset($constCompany->img_company)}}">
                                        @endif
                                    </a>
                                </div>
                                <div class="col-6 collapse-close">
                                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
                                        <span></span>
                                        <span></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item">
                                @if (Route::has('login'))
                                    <div class="top-right links">
                                        @auth
                                            <a class="nav-link nav-link-icon" href="{{ url('/home') }}">
                                                <i class="fa fa-home"></i>
                                                <span class="nav-link-inner--text d-lg-none">Home</span></a>
                                        @else
                                            <a class="nav-link nav-link-icon" href="{{ route('login') }}">
                                                <i class="fa fa-sign-in-alt"></i>
                                                <span class="nav-link-inner--text d-lg-none">Login</span>
                                            </a>
                                        @endauth
                                    </div>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid bg-dark text-center justify-content-center d-flex py-4">
                <form class="navbar-search navbar-search-light form-inline mr-sm-3" id="navbar-search-main">
                    <div class="form-group mb-0">
                      <div class="input-group input-group-alternative input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input class="form-control" placeholder="Cari Apa ?" type="text">
                      </div>
                    </div>
                    <button type="button" class="close" data-action="search-close" data-target="#navbar-search-main" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                  </form>
            </div>
            <main>
                <div class="container mt-n-5">
                    <h1 class="mb-n-6 text-center font-weight-bolder">OUR PRODUCT</h1>
                    <div class="row">
                        @foreach ($g_products as $i_p)
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-n-6 text-center">
                                <img src="{{asset($i_p->img)}}" class="card-img" style="border-radius: 10px" alt="">
                                <span class="h2">{{strtoupper($i_p->nama_product)}}</span>
                            </div>
                        @endforeach
                    </div>
                    @if(($count_Allproducts - $g_products->count()) > 0)
                    <h1 class="mb-n-6 text-center font-weight-bolder">AND {{$count_Allproducts - $g_products->count()}} MORE</h1>
                    @endif
                </div>
            </main>
            <footer class="footer pt-0 pb-0">
                <div class="d-flex align-items-center justify-content-lg-between bg-dark py-5 ">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 mb-3 d-flex flex-column align-items-start">
                                <div class="item d-flex flex-column mb-3">
                                    <div class="">
                                        <i class="fa fa-phone-alt"></i>&nbsp;phone
                                    </div>
                                    <span class="text-muted">{{$constCompany->phone}}</span>
                                </div>
                                <div class="item d-flex flex-column">
                                    <div class="">
                                        <i class="fa fa-info-circle"></i>&nbsp;about us
                                    </div>
                                    <span class="text-muted">{{$constCompany->about}}</span>
                                </div>
                            </div>
                            <div class="col-lg-6 justify-content-center text-left">
                                <div class="item d-flex flex-column">
                                    <div class="">
                                        <i class="fa fa-map-marker-alt"></i>&nbsp;address
                                    </div>
                                    <span class="text-muted">{{$constCompany->address}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="copyright text-center mt-3 pt-4 text-muted align-items-center">
                            &copy; 2020 <a href="{{ url('/') }}" class="font-weight-bold ml-1" target="_blank">{{$constNamaCompany}}</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/js-cookie/js.cookie.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/chart.js/dist/Chart.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/chart.js/dist/Chart.extension.js') }}"></script>
        <script src="{{ asset('assets/js/argon.js?v=1.2.0') }}"></script>
        <script>
            $(document).click(function(){
                $('body').removeClass('g-sidenav-pinned');
            });
            $('img').attr('draggable', false);
        </script>
    </body>
</html>
