@section('main-content')
<div class="main-content" id="panel">
    <!-- Topnav -->
    <nav class="navbar navbar-top navbar-expand bg-gradient-primary navbar-dark border-bottom " style="min-height: 25px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            
          @if(Auth::guard('web')->check())
          <ul class="navbar-nav align-items-center mr-auto mr-md-auto ">
            <a href="{{route('home')}}" class="nav-link font-weight-bold text-default">Nama Toko</a>
          </ul>
          @endif
          <!-- Navbar links -->
          <ul class="navbar-nav align-items-center ml-auto ml-md-auto ">
            {{-- <li class="nav-item d-xl-none"> --}}
            @if(!Auth::guard('web')->check())
            <li class="nav-item d-xl-none">
              <!-- Sidenav toggler -->
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
                          <p class="text-sm mb-0">@currency($item->buy_value * $item->product->price ) ({{$item->buy_value}} x @currency($item->product->price))</p>
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
                <!-- Dropdown header -->
                {{-- <div class="px-3 py-3">
                  <h6 class="text-sm text-muted m-0">You have <strong class="text-primary">13</strong> notifications.</h6>
                </div> --}}
                <!-- List group -->
                
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
                          {{-- <img alt="Image placeholder" src="../assets/img/theme/team-1.jpg" class="avatar rounded-circle"> --}}
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
                <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
                @else
                  <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">Tidak ada aktivitas terbaru</a>
                @endif
              </div>
            </li>
            @endif
            <li class="nav-item dropdown">
              <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ni ni-ungroup  text-dark"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-dark bg-default  dropdown-menu-right ">
                <div class="row shortcuts px-4">
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-red">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                    <small>Calendar</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-orange">
                      <i class="ni ni-email-83"></i>
                    </span>
                    <small>Email</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-info">
                      <i class="ni ni-credit-card"></i>
                    </span>
                    <small>Payments</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-green">
                      <i class="ni ni-books"></i>
                    </span>
                    <small>Reports</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-purple">
                      <i class="ni ni-pin-3"></i>
                    </span>
                    <small>Maps</small>
                  </a>
                  <a href="#!" class="col-4 shortcut-item">
                    <span class="shortcut-media avatar rounded-circle bg-gradient-yellow">
                      <i class="ni ni-basket"></i>
                    </span>
                    <small>Shop</small>
                  </a>
                </div>
              </div>
            </li>
          </ul>
          <ul class="navbar-nav align-items-center ml-1 ml-md-0 ">
            <li class="nav-item dropdown">
              <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="media align-items-center">
                  <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="{{ asset('assets/img/theme/team-4.jpg') }}">
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
                <a href="/my" class="dropdown-item">
                  <i class="ni ni-single-02"></i>
                  <span>My profile</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-settings-gear-65"></i>
                  <span>Settings</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-calendar-grid-58"></i>
                  <span>Activity</span>
                </a>
                <a href="#!" class="dropdown-item">
                  <i class="ni ni-support-16"></i>
                  <span>Support</span>
                </a>
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
    <!-- Page content -->
    {{-- <div class="container-fluid"> --}}
    @yield('content')
      @yield('footer')
      <!-- Footer -->
      {{-- <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6">
            <div class="copyright text-center  text-lg-left  text-muted">
              &copy; 2020 <a href="#" class="font-weight-bold ml-1" target="_blank">Site Link</a>
            </div>
          </div>
          <div class="col-lg-6">
            <ul class="nav nav-footer justify-content-center justify-content-lg-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div>
        </div>
      </footer> --}}
    {{-- </div> --}}
  </div>
@endsection