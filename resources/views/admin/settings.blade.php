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
            <div class="col-xl-12">
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