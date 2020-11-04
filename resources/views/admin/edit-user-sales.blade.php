@extends('dashboard-layout.master')
@section('header-content')
<div class="header pb-6 d-flex align-items-center">
    <span class="mask bg-gradient-danger opacity-8"></span>
    <div class="container-fluid">
        
    </div>
</div>
@endsection

@section('content')
    <div class="container-fluid mt--3">
        <div class="row mr-2 ml-2">
            <div class="col-xl-4">
                <div class="card shadow-none">
                    <div class="card-profile-image mb-5">
                        <a href="#">
                            <img src="{{ asset('assets/img/theme/team-1.jpg')}}" class="rounded-circle">
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                          <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center">
                              <div>
                                <span class="heading">22</span>
                                <span class="description">Total Orders</span>
                              </div>
                              <div>
                                <span class="heading">10</span>
                                <span class="description">Photos</span>
                              </div>
                              <div>
                                <span class="heading">89</span>
                                <span class="description">Comments</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="text-center">
                          <h5 class="h3">
                            {{$dataUser->name}} <span class="text-muted">({{$dataUser->username}})</span>
                          </h5>
                          <div class="h5 font-weight-300">
                            <i class="fa fa-phone mr-2"></i>{{$dataUser->phone}}
                          </div>
                          <div class="h5 mt-4">
                            {{$dataUser->address}}
                          </div>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card shadow-none">
                    <div class="card-header h3">Edit User Info</div>
                </div>
            </div>
        </div>
    </div>
@endsection