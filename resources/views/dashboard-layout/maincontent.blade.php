@section('main-content')
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand navbar-dark border-bottom bg-primary" style="min-height: 25px; background-size: cover; background-position: center top;">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
          @if(Auth::guard('web')->check())
          <ul class="navbar-nav align-items-center mr-auto mr-md-auto ">
            <a href="{{route('home')}}" class="nav-link font-weight-bold text-default">
            @isset($company_profile->name)
                {{$company_profile->name}}
            @else
              @php
                  $constCompany = App\AboutUs::first();
                  if($constCompany == null) {
                      $constNamaCompany = "App POS";
                  }else{
                      $constNamaCompany = $constCompany->name;
                  }
              @endphp
              {{$constNamaCompany}}
            @endisset
            </a>
          </ul>
          @endif
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-auto ml-md-auto ">
            @if(!Auth::guard('web')->check())
            <li class="nav-item d-xl-none">
              <div class="text-left">
                <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                  <div class="sidenav-toggler-inner text-left">
                    <i class="sidenav-toggler-line bg-dark"></i>
                    <i class="sidenav-toggler-line bg-dark"></i>
                    <i class="sidenav-toggler-line bg-dark"></i>
                  </div>
                </div>
              </div>
            </li>
            @endif
            @if(Auth::guard('web')->check())
            <li class="nav-item dropdown">
              <a class="btn d-sm-none text-dark" href="#" data-action="search-show" data-target="#navbar-search-main">
                <i class="ni ni-zoom-split-in"></i>
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if ($cart->count() > 0)
                  <i class="fa fa-shopping-cart text-warning"></i>
                  <span class="position-absolute badge badge-default">{{ $cart->count() }}</span>
                  @else
                  <i class="fa fa-shopping-cart text-dark"></i>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                <!-- Dropdown header -->
                <div class="px-3 py-3">
                  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">{{$cart->count()}}</strong> items in cart.</h6>
                </div>
                <!-- List group -->
                <div class="list-group list-group-flush">
                  @foreach ($cart as $item)
                    <a href="#!" class="list-group-item list-group-item-action">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <!-- Avatar -->
                          <img alt="Image placeholder" src="{{asset($item->product->img)}}" class="avatar rounded-circle">
                        </div>
                        <div class="col ml--2">
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <h4 class="mb-0 text-sm">{{$item->product->nama_product}}</h4>
                            </div>
                            <div class="text-right text-muted">
                              <small>{{$item->buy_value}} {{$item->product->unit->unit}}</small>
                            </div>
                          </div>
                          
                          @if ($item->custom_price != $item->product->price)
                          <p class="text-sm mb-0">@currency($item->buy_value * $item->custom_price) ({{$item->buy_value}} x @currency($item->custom_price))</p>
                          @else
                          <p class="text-sm mb-0">@currency($item->buy_value * $item->product->price ) ({{$item->buy_value}} x @currency($item->product->price))</p>
                          @endif
                        </div>
                      </div>
                    </a>
                  @endforeach
                </div>
                <!-- View all -->
                <a href="{{route('checkout')}}" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
              </div>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @if ($cekTransactions->count() > 0)
                  <i class="ni ni-bell-55 text-danger"></i>
                  <span class="position-absolute badge badge-default">{{$cekTransactions->count()}}</span>
                @else  
                  <i class="ni ni-bell-55 text-dark"></i>
                @endif
              </a>
              <div class="dropdown-menu dropdown-menu-xl  dropdown-menu-right  py-0 overflow-hidden">
                
                @if ($cekTransactions->count() > 0)
                <div class="list-group list-group-flush">
                  @php
                    $notifcount = 0;    
                  @endphp
                  @foreach ($cekTransactions->take(5) as $itemT)
                    <a href="#!" class="list-group-item list-group-item-action">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <!-- Avatar -->
                          <i class="fa fa-receipt avatar rounded-circle"></i>
                        </div>
                        <div class="col ml--2">
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                            <h4 class="mb-0 text-sm">Pesanan #{{$itemT->transaction_id}}</h4>
                            </div>
                            <div class="text-right text-muted">
                              <small>{{Carbon\Carbon::parse($itemT->created_at)->diffForHumans()}}</small>
                            </div>
                          </div>
                          <p class="text-sm mb-0">Menunggu diproses</p>
                        </div>
                      </div>
                    </a>
                  @endforeach
                </div>
                <!-- View all -->
                <a href="{{route('my-orders')}}" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
                @else
                  <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">Tidak ada aktivitas terbaru</a>
                @endif
              </div>
            </li>
            @endif
          </ul>
          <ul class="navbar-nav align-items-center ml-1 ml-md-0 ">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="{{ asset('user-img/user-img-default.png') }}">
                  </span>
                  <div class="media-body  ml-2  d-none d-lg-block">
                  <span class="mb-0 text-sm  text-dark font-weight-bold">{{ Auth::user()->name }}</span>
                  </div>
                </div>
              </a>
              <div class="dropdown-menu  dropdown-menu-right ">
                <div class="dropdown-header noti-title">
                  <h6 class="text-overflow m-0">Welcome!</h6>
                </div>
                @if(Auth::guard('web')->check())
                  <a href="/my" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>My profile</span>
                  </a>
                  <a href="{{route('my-orders')}}" class="dropdown-item">
                    <i class="fa fa-receipt"></i>
                    <span>Riwayat Pembelian</span>
                  </a>
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="ni ni-user-run"></i>
                  <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    @yield('header-content')
    @yield('content')
    @yield('footer')
  </div>
@endsection