@extends('dashboard-layout.master')
@section('content')
    <div class="header pb-6 d-flex align-items-center" style="min-height: 500px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-primary opacity-8"></span>
        <div class="container-fluid d-flex align-items-center">
            <div class="row">
                <div class="col-lg-7 col-md-10">
                    <h1 class="display-2 text-white">{{ $aboutUs->name }}</h1>
                    <p class="text-white mt-0 mb-5">{{ $aboutUs->about }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--6 mb-5">
      
      @if(session()->get('success'))
      <div class="alert alert-success">
          {{session()->get('success')}}
      </div>
      @endif
      @if(session()->get('error'))
      <div class="alert alert-danger">
         {{session()->get('error')}}
      </div>
      @endif
        <div class="row">
            <div class="col-xl-4">
                <div class="card card-profile">
                    <img src="{{asset('assets/img/theme/img-1-1000x600.jpg')}}" alt="Image placeholder" class="card-img-top bg-default">
                    <div class="row justify-content-center">
                      <div class="col-lg-3 order-lg-2">
                        <div class="card-profile-image">
                          <a href="#">
                            <img src="{{ asset($aboutUs->img_company) }}" class="rounded-circle">
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
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
                    </div>
                  </div>
            </div>
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Profile</a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Logo</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">Change Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                              <form action="" method="post" id="formInfo">
                                @csrf
                                  <div class="pl-lg-4">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                  <label class="form-control-label" for="info-name-company">Nama Toko</label>
                                                  <input id="info-name-company" class="form-control form-control-alternative" value="{{ $aboutUs->name }}" type="text" name="info_name_company" disabled required>
                                              </div>
                                            </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-lg-6">
                                              <div class="form-group">
                                                  <label for="info-phone-company" class="form-control-label">Hp</label>
                                                  <input class="form-control" type="number" name="info_phone_company" value="{{ $aboutUs->phone }}" id="info-phone-company" disabled required>
                                              </div>
                                          </div>
                                          <div class="col-lg-6">
                                              <div class="form-group">
                                                  <label for="info-telp-company" class="form-control-label">Telp</label>
                                                  <input class="form-control" type="text" value="" id="info-telp-company" disabled>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <hr class="my-4">
                                  <div class="pl-lg-4">
                                      <div class="form-group">
                                          <label for="infoalamat" class="form-control-label">Alamat</label>
                                          <textarea id="infoalamat" rows="4" class="form-control" name="info_alamat_company" disabled>{{ $aboutUs->address }}</textarea>
                                      </div>
                                      <div class="form-group">
                                          <label for="aboutInfo" class="form-control-label">About</label>
                                          <textarea id="aboutInfo" rows="4" class="form-control" name="info_aboutus_company" disabled>{{ $aboutUs->about }}</textarea>
                                        </div>
                                  </div>   
                                <div id="btnaction" class="text-right">
                                    <button id="editInfobtn" type="button" class="btn btn-sm btn-light btn-icon">edit</button>
                                    <button id="simpanUpdate" type="submit" class="btn btn-sm btn-success btn-icon d-none">Update</button>
                                </div>
                              </form>   
                            </div>
                            {{-- <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                              <div class="row">
                                <div class="col-lg-4">
                                  <img style="max-width: 200px" src="{{ asset($aboutUs->img_company) }}" alt="">
                                </div>
                                <div class="col-lg-8">
                                  <form action="" method="POST">
                                      <div class="form-group">
                                        <label for="logoC" class="form-control-label">Logo</label>
                                          <input type="file" class="form-control-file" name="" id="logoC">
                                          <small class="text-primary"><strong>berbentuk : .jpg, .png, .jpeg</strong></small>
                                      </div>
                                    </form>
                                </div>
                              </div>  
                            </div> --}}
                            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                              <form action="{{ route('admin.update-settings.cp') }}" method="post">
                                  @csrf
                                  <div class="form-group">
                                    <label for="oldpass" class="form-control-label">Kata sandi lama</label>
                                    <input type="password" name="oldpassword" class="form-control" id="oldpass">
                                  </div>
                                  <div class="form-group">
                                    <label for="newpass" class="form-control-label">Kata sandi baru</label>
                                    <input type="password" name="newpass" class="form-control" id="newpass">
                                  </div>
                                  <div class="form-group">
                                    <label for="newpass2" class="form-control-label">Ulangi Kata sandi baru</label>
                                    <input type="password" name="newpass2" class="form-control" id="newpass2">
                                  </div>
                                  <div class="form-group">
                                    <button class="btn btn-block btn-success">ganti password</button>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
      $('#editInfobtn').click(function(){
        $('#info-name-company').removeAttr("disabled");
        $('#info-phone-company').removeAttr("disabled");
        $('#infoalamat').removeAttr("disabled");
        $('#aboutInfo').removeAttr("disabled");
        $('#simpanUpdate').removeClass("d-none");
        $('#formInfo').attr('action', '{{route('admin.update-settings')}}');
        $('#editInfobtn').addClass("d-none");
      });
    </script>
@endpush