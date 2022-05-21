<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="POS APP">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @if (request()->has('category'))
        @php
             $g_products = App\Product::where('product_status','show')->where('category_id',request()->category)->inRandomOrder()->get();
             $count_Allproducts = App\Product::where('product_status','show')->where('category_id',request()->category)->count();
        @endphp
        @elseif(request()->has('cari'))
        @php
             $g_products = App\Product::where('product_status','show')->where('nama_product',"like",'%'.request()->cari.'%')->inRandomOrder()->get();
             $count_Allproducts = App\Product::where('product_status','show')->where('nama_product',"like",'%'.request()->cari.'%')->count();
        @endphp
        @else
        @php
             $g_products = App\Product::where('product_status','show')->inRandomOrder()->get();
             $count_Allproducts = App\Product::where('product_status','show')->count();
        @endphp
        @endif
        @php
           
            $category_products = App\CategoryProduct::where('status',1)->inRandomOrder()->get();

            $g_categ = App\CategoryProduct::where('status',1)->paginate(10);
            $constCompany = App\AboutUs::first();
            if($constCompany == null) {
                $constNamaCompany = "App POS";
            }else{
                $constNamaCompany = $constCompany->name;
            }
        @endphp
        {{$constNamaCompany}}
    </title>
    
    <link rel="icon" href="{{ asset('assets/img/brand/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="css/font.css" type="text/css">
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/ogani-master/css/style.css') }}" type="text/css">
</head>
    <body>
        {{-- <div id="preloder">
            <div class="loader"></div>
        </div> --}}
        <!-- Humberger Begin -->
        <div class="humberger__menu__overlay"></div>
        <div class="humberger__menu__wrapper">
            <div class="humberger__menu__logo">
                <a href="/"><img src="{{asset('assets/vendor/ogani-master/img/logo.jpg')}}" alt=""></a>
            </div>
            <div class="humberger__menu__widget">
                <div class="header__top__right__auth">
                    <a href="{{route('login')}}"><i class="fa fa-user"></i> Login</a>
                </div>
            </div>
            <nav class="humberger__menu__nav mobile-menu">
                <ul>
                    <li class="active"><a href="/">Utama</a></li>
                    <li><a href="#produk">Produk</a></li>
                    <li><a href="#kategori">Kategori</a>
                        <ul class="header__menu__dropdown">
                            @foreach ($category_products as $cp)
                            <li><a href="products?category={{$cp->id}}"> {{ucwords($cp->name)}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <li><a href="#tentang">Tentang</a></li>
                </ul>
            </nav>
            <div id="mobile-menu-wrap"></div>
        </div>
        <!-- Humberger End -->

         <!-- Header Section Begin -->
        <header class="header">
            <div class="header__top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="header__top__left">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="header__top__right">
                                <div class="header__top__right__auth">
                                    <a href="{{route('login')}}"><i class="fa fa-user"></i> Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="header__logo">
                            <a href="/">
                                <img src="{{asset('assets/vendor/ogani-master/img/logo.jpg')}}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <nav class="header__menu">
                            <ul>
                                <li class="active"><a href="/">Utama</a></li>
                                <li><a href="#produk">Produk</a></li>
                                <li><a href="#kategori">Kategori</a></li>
                                <li><a href="#tentang">Tentang</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-3">
                        <div class="header__cart">
                            <div class="header__cart__price">item: <span>0</span></div>
                        </div>
                    </div>
                </div>
                <div class="humberger__open">
                    <i class="fa fa-bars"></i>
                </div>
            </div>
        </header>
        <!-- Header Section End -->

         <!-- Hero Section Begin -->
        <section class="hero">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="hero__categories">
                            <div class="hero__categories__all">
                                <i class="fa fa-bars"></i>
                                <span>Semua Kategori</span>
                            </div>
                            <ul>
                                @foreach ($category_products as $cp)
                                <li><a href="?category={{$cp->id}}">{{ucwords($cp->name)}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="hero__search">
                            <div class="hero__search__form">
                                <form action="#">
                                    <input type="text" name="cari" placeholder="Cari apa?">
                                    <button type="submit" class="site-btn">CARI</button>
                                </form>
                            </div>
                        </div>
                        <div class="hero__item" style="background-color: #F5F5F5">
                            <div class="hero__text">
                                <span>Menyediakan</span>
                                <h2>ATK, Komputer, Elektronik, Aktivitas Kantor (Fotocopy)</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero Section End -->

            <!-- Categories Section Begin -->
        <section class="categories" id="kategori">
            <div class="container">
                <div class="row">
                    <div class="categories__slider owl-carousel">
                        @foreach ($category_products as $cp)
                            <div class="col-lg-3">
                                <div class="categories__item set-bg" data-setbg="{{asset('assets/vendor/ogani-master/img/categories/box.png')}}">
                                    <h5><a href="?category={{$cp->id}}">{{$cp->name}}</a></h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!-- Categories Section End -->

         <!-- Featured Section Begin -->
        <section class="featured spad" id="produk">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2>Produk</h2>
                        </div>
                        <div class="featured__controls">
                            <ul>
                                <li class="active" data-filter="*">All</li>
                                @foreach ($category_products->take(4) as $cp)
                                    @php
                                        $cExplod = explode(' ', $cp->name);
                                        $cImplode = implode('-', $cExplod);
                                    @endphp
                                    <li data-filter=".{{$cImplode}}">{{ucwords($cp->name)}}</li>
                                @endforeach
                                <li><a class="text-dark" href="/category">Lainnya</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row featured__filter">
                    @foreach ($g_products as $i_p)
                        @isset($i_p->category)
                                @php
                                    $cExplodProduct = explode(' ', $i_p->category->name);
                                    $cImplodeProduct = implode('-', $cExplodProduct);
                                @endphp
                        @endisset
                        <div class="col-lg-3 col-md-4 col-sm-6 mix @isset($cImplodeProduct){{$cImplodeProduct}}@endisset">
                            <div class="featured__item">
                                <div class="featured__item__pic set-bg" data-setbg="{{asset($i_p->img)}}">
                                    <ul class="featured__item__pic__hover">
                                        @guest   
                                            <li><a href="/login"><i class="fa fa-shopping-cart"></i></a></li>
                                        @endguest
                                    </ul>
                                </div>
                                <div class="featured__item__text">
                                    <h6><a href="products?details={{$i_p->id}}">{{strtoupper($i_p->nama_product)}}</a></h6>
                                    <h5>@currency($i_p->price)</h5>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    {{-- <div class="col-lg-3 col-md-4 col-sm-6 mix fastfood vegetables">
                        <div class="featured__item">
                            <div class="featured__item__pic set-bg" data-setbg="img/featured/feature-8.jpg">
                                <ul class="featured__item__pic__hover">
                                    <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                    <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                    <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                </ul>
                            </div>
                            <div class="featured__item__text">
                                <h6><a href="#">Crab Pool Security</a></h6>
                                <h5>$30.00</h5>
                            </div>
                        </div>
                    </div> --}}
                </div>
                
                <div class="text-center">
                    @if(($count_Allproducts - $g_products->count()) > 0)
                        <a href="/products" class="mb-n-6 text-center font-weight-bolder"><button type="button" class="btn btn-outline-primary">Lihat Semua</button></a>
                    @endif
                </div>
            </div>
        </section>
        <!-- Featured Section End -->

            <!-- Footer Section Begin -->
        <footer class="footer spad" id="tentang">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__about">
                            <div class="footer__about__logo">
                                <a href="/"><img src="{{asset('assets/vendor/ogani-master/img/logo.jpg')}}" alt=""></a>
                            </div>
                            <ul>
                                <li>Alamat: {{$constCompany->address}}</li>
                                <li>No. Telepon: {{$constCompany->phone}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="footer__widget">
                            <p>{{$constCompany->about}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Section End -->

        <!-- Js Plugins -->
        <script src="{{ asset('assets/vendor/ogani-master/js/jquery-3.3.1.min.js') }}"></script>
        
        <script src="{{ asset('assets/vendor/ogani-master/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/jquery.nice-select.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/jquery.slicknav.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/mixitup.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/ogani-master/js/main.js') }}"></script>
    </body>
</html>
