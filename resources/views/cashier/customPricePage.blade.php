@extends('dashboard-layout.master')

@section('content')
    <div class="container-fluid mt-4 p-3">
        <h1>Pilih sales</h1>
        <form action="" method="POST" class="form-inline">
            <input type="text" class="form-control form-control-alternative" placeholder="cari">
            <button class="btn btn-icon-only btn-info"><i class="fa fa-search"></i></button>
        </form>
        <div class="row mt-3 py-3">
            @foreach ($sales as $user)
                <div class="col-lg-3 col-md-4">
                    <a href="{{route('cashier.customprice.set',$user->id)}}">
                        <div class="card shadow-none shadow-sm--hover">
                            <div class="card-body text-dark text-center">
                                {{$user->name}}
                                <h3 class="mt-2">
                                    <i class="fa fa-phone"></i> {{$user->phone}}
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection