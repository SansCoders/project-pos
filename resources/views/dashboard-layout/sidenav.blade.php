@section('sidenav')

@if(Auth::guard('web')->check())
@else
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" style="z-index: 1" id="sidenav-main">
    <div class="scrollbar-inner">
      <div class="sidenav-header d-flex  align-items-center">
        <a class="navbar-brand d-flex flex-column" href="javascript:void(0)">
            @php
            $constCompany = App\AboutUs::first();
            if($constCompany == null) {
                $constNamaCompany = "App POS";
            }else{
                $constNamaCompany = $constCompany->name;
            }
            @endphp
          <i class="ni ni-shop"></i><h2>{{$constNamaCompany}}</h2>
        </a>
        <div class=" ml-auto ">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block active" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">

            @if(Auth::guard('admin')->check())
            <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link {{ set_active('admin.home') }}" href="{{route('admin.home')}}">
                    <i class="ni ni-tv-2 text-default"></i>
                    <span class="nav-link-text">Dashboard</span>
                  </a>
                </li>
            </ul>
            <hr class="my-3">
            <h6 class="navbar-heading p-0 text-muted">
                <span class="docs-normal">Manajemen</span>
            </h6>
            <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link {{ set_active('admin.categorys') }}" href="{{route('admin.categorys')}}">
                      <i class="ni ni-box-2 text-default"></i>
                      <span class="nav-link-text">Kategori Produk</span>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ set_active('admin.products') }}" href="{{route('admin.products')}}">
                      <i class="ni ni-bag-17 text-default"></i>
                      <span class="nav-link-text">Produk</span>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ set_active('admin.common_units') }}" href="{{route('admin.common_units')}}">
                      <i class="ni ni-tag text-default"></i>
                      <span class="nav-link-text">Satuan Umum</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed {{ set_active('admin.users-sales') }} || {{ set_active('admin.users-cashier') }}" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                      <i class="fa fa-users text-default"></i>
                      <span class="nav-link-text">Pengguna</span>
                    </a>
                    <div class="collapse" id="navbar-tables">
                      <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                          <a href="{{route('admin.users-sales')}}" class="nav-link ">
                            <span class=""> Sales </span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{route('admin.users-cashier')}}" class="nav-link">
                            <span class=""> Kasir </span>
                          </a>
                        </li>
                      </ul>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav mb-md-3">
              <li class="nav-item">
                <a class="nav-link collapsed" href="#navbar-log" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                  <i class="fa fa-paperclip text-default"></i>
                  <span class="nav-link-text">Aktivitas</span>
                </a>
                <div class="collapse" id="navbar-log">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{route('admin.users-sales')}}" class="nav-link ">
                        <span class=""> Stock </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            <div class="my-3"></div>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link {{ set_active('admin.settings') }}" href="{{ route('admin.settings') }}">
                  <i class="ni ni-settings-gear-65 text-default"></i>
                  <span class="nav-link-text">Settings</span>
                </a>
              </li>
            </ul>
            @elseif(Auth::guard('cashier')->check())
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link {{ set_active('cashier.home') }}" href="{{route('cashier.home')}}">
                  <i class="ni ni-tv-2 text-default"></i>
                    <span class="nav-link-text">Dashboard</span>
                  </a>
                </li>
              <li class="nav-item">
                <a class="nav-link {{ set_active('cashier.transaction') }}" href="{{route('cashier.transaction')}}">
                  <i class="ni ni-basket text-default"></i>
                    <span class="nav-link-text">Transaction</span>
                  </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav mb-md-3">
              <li class="nav-item">
                <a class="nav-link {{ set_active('cashier.products') }}" href="{{route('cashier.products')}}">
                  <i class="ni ni-bag-17 text-default"></i>
                    <span class="nav-link-text">Produk</span>
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link collapsed {{ set_active('stock.add') }}" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                  <i class="ni ni-box-2 text-default"></i>
                  <span class="nav-link-text">Stocks</span>
                </a>
                <div class="collapse" id="navbar-tables">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{route('stock.add')}}" class="nav-link "> 
                        <span class=""> Stock In </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link "> 
                        <span class=""> Stock Out </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <span class=""> Stock Retur </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link collapsed" href="#navbar-reports" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                  <i class="ni ni-chart-bar-32 text-default"></i>
                  <span class="nav-link-text">Reports</span>
                </a>
                <div class="collapse" id="navbar-reports">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="#" class="nav-link "> 
                        <span class=""> summary </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            @else
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link {{ set_active('cashier.home') }}" href="{{route('cashier.home')}}">
                  <i class="ni ni-tv-2 text-default"></i>
                    <span class="nav-link-text">Dashboard</span>
                  </a>
                </li>
            </ul>
            <hr class="my-3">
            <ul class="navbar-nav mb-md-3">
              <li class="nav-item">
                <a class="nav-link {{ set_active('cashier.products') }}" href="{{route('cashier.products')}}">
                  <i class="ni ni-bag-17 text-default"></i>
                    <span class="nav-link-text">Produk</span>
                  </a>
              </li>
              <li class="nav-item">
                <a class="nav-link collapsed {{ set_active('stock.add') }}" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                  <i class="ni ni-box-2 text-default"></i>
                  <span class="nav-link-text">Stocks</span>
                </a>
                <div class="collapse" id="navbar-tables">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="{{route('stock.add')}}" class="nav-link "> 
                        <span class=""> Stock In </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link "> 
                        <span class=""> Stock Out </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <span class=""> Stock Retur </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link collapsed" href="#navbar-reports" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                  <i class="ni ni-chart-bar-32 text-default"></i>
                  <span class="nav-link-text">Reports</span>
                </a>
                <div class="collapse" id="navbar-reports">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="#" class="nav-link "> 
                        <span class=""> summary </span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
            @endif

        </div>
      </div>
    </div>
</nav>  
@endif
@endsection
