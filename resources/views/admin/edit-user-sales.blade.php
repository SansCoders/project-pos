@extends('dashboard-layout.master')
@section('content')
    <div class="contaner-fluid mt-5">
        <div class="row">
            <div class="col-lg-4">
                {{$dataUser->name}}
            </div>
        </div>
    </div>
@endsection