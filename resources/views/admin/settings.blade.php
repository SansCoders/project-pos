@extends('dashboard-layout.master')
@section('content')
    <div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-danger opacity-8"></span>
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-7 col-md-10">
                    <h1 class="display-2 text-white">{{ $aboutUs->name }}</h1>
                    <p class="text-white mt-0 mb-5">{{ $aboutUs->about }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-profile">
                    <img src="../assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top bg-default">
                    <div class="row justify-content-center">
                      <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                          <a href="#">
                            <img src="{{ $aboutUs->img_company }}" class="rounded-circle">
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                      <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-sm btn-info  mr-4 ">Connect</a>
                        <a href="#" class="btn btn-sm btn-default float-right">Message</a>
                      </div>
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col">
                          <div class="card-profile-stats d-flex justify-content-center">
                            @php
                                $countrowSales = DB::table('users')->count();
                                $countrowCashiers = DB::table('cashiers')->count();
                                $countrowAdmins = DB::table('admins')->count();
                            @endphp
                            <div>
                              <span class="heading">
                                  {{$countrowSales}}
                              </span>
                              <span class="description">Sales</span>
                            </div>
                            <div>
                              <span class="heading">
                                {{$countrowCashiers}}</span>
                              <span class="description">Cashiers</span>
                            </div>
                            <div>
                              <span class="heading">
                                {{$countrowAdmins}}</span>
                              <span class="description">Admins</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="text-center">
                        <h5 class="h3">
                          Jessica Jones<span class="font-weight-light">, 27</span>
                        </h5>
                        <div class="h5 font-weight-300">
                          <i class="ni location_pin mr-2"></i>Bucharest, Romania
                        </div>
                        <div class="h5 mt-4">
                          <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                        </div>
                        <div>
                          <i class="ni education_hat mr-2"></i>University of Computer Science
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Details</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">Change Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                <form action="" method="POST">
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="info-name-company">Nama Toko</label>
                                                    <input id="info-name-company" class="form-control form-control-alternative" value="{{ $aboutUs->name }}" type="text" name="info_name_company" disabled>
                                                </div>
                                              </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="info-phone-company" class="form-control-label">Hp</label>
                                                    <input class="form-control" type="number" value="62{{ $aboutUs->phone }}" id="info-phone-company" disabled>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="info-telp-company" class="form-control-label">Telp</label>
                                                    <input class="form-control" type="text" value="(123) 123456" id="info-telp-company" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="pl-lg-4">
                                        <div class="form-group">
                                            <label class="form-control-label">Alamat</label>
                                            <textarea rows="4" class="form-control" disabled>{{ $aboutUs->address }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-control-label">About</label>
                                            <textarea rows="4" class="form-control" disabled>{{ $aboutUs->about }}</textarea>
                                          </div>
                                    </div>
                                </form>
                                <div class=" text-right">
                                    <button class="btn btn-sm btn-light btn-icon">edit</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                <p class="description">Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                            </div>
                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                <p class="description">Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection