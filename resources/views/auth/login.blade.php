@extends('dashboard-layout.auth-master')

@section('content')
<div class="container mt--8 pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
          <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>Sign in</small></div>
            <div class="btn-wrapper text-center">
              
            @isset($url)
              @if ($url != "admin")
              <a href="{{ url("login/admin") }}" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/admin.svg')}}"></span>
                <span class="btn-inner--text">Admin</span>
              </a>
              @endif
              @if ($url != "cashier")
              <a href="{{ url("login/cashier") }}" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/cashier.svg"></span>
                <span class="btn-inner--text">Kasir</span>
              </a>
              @endif
              <a href="{{ url("login") }}" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><i class="fa fa-user"></i></span>
                <span class="btn-inner--text">Sales</span>
              </a>
            @else
            <a href="{{ url("login/admin") }}" class="btn btn-neutral btn-icon">
              <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/admin.svg')}}"></span>
              <span class="btn-inner--text">Admin</span>
            </a>
            <a href="{{ url("login/cashier") }}" class="btn btn-neutral btn-icon">
              <span class="btn-inner--icon"><img src="{{ asset('assets/img/icons/common/cashier.svg')}}"></span>
              <span class="btn-inner--text">Kasir</span>
            </a>
            @endisset
            </div>
          </div>
          <div class="card-body px-lg-5 py-lg-5">
          <b class="text-center">
            Login @isset($url)
                {{ $url }}
              @endisset
            </b>
            @isset($url)
              <form  role="form" method="POST" action='{{ url("login/$url") }}' aria-label="{{ __('Login') }}">
            @else
              <form  role="form" method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
            @endisset
            @csrf
              <div class="form-group mb-3">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                  </div>
                  <input id="username" class="form-control @error('username') is-invalid @enderror" placeholder="username" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                  @error('username')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  </div>
                  <input id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">Sign in</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
