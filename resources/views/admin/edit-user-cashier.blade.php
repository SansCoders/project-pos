@extends('dashboard-layout.master')
@section('header-content')
<div class="header pb-6 d-flex align-items-center">
    <span class="mask bg-primary"></span>
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
                        <a href="#" >
                            <img src="{{asset('user-img/user-img-default.png')}}" class="rounded-circle">
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                          <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center">
                              <div>
                                <span class="heading">Cashier</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="text-center">
                          <h5 class="h3">
                            {{$dataUser->name}}
                          </h5>
                        </div>
                      </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card shadow-none">
                    <div class="card-header">
                      <ul class="nav nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Profile</a>
                        </li>
                        <li class="nav-item">
                              <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">Change Password</a>
                        </li>
                      </ul>
                  </div>
                    <div class="card-body">
                      <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                          @if ($errors->any())
                              <div class="alert alert-danger">
                                  <ul>
                                      @foreach ($errors->all() as $error)
                                          <li>{{ $error }}</li>
                                      @endforeach
                                  </ul>
                              </div>
                          @endif
                          <form action="{{ route('admin.users-cashier.edit-put') }}" method="post" id="formData">
                            @csrf
                            @method('PUT')
                              <input type="hidden" name="iduser" value="{{ $dataUser->id }}">
                              <div class="pl-lg-4">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label class="form-control-label" for="info-name">Nama</label>
                                              <input id="info-name" class="form-control form-control-alternative" value="{{ $dataUser->name }}" type="text" name="info_name" disabled required>
                                          </div>
                                        </div>
                                  </div>
                              </div>
                            <div id="btnaction" class="text-right">
                                <button id="editbtn" type="button" class="btn btn-sm btn-light btn-icon">edit</button>
                                <button id="simpanUpdate" type="submit" class="btn btn-sm btn-success btn-icon d-none">Update</button>
                            </div>
                          </form>   
                        </div>
                        <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                          <form action="{{route('admin.changepass')}}" method="POST">
                            @csrf
                            @method("PUT")
                            <div class="form-group">
                                <label for="passold">Kata Sandi Lama</label>
                                <input id="passold" type="password" name="" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="passnew">Kata Sandi Baru</label>
                                <input id="passnew" type="password" name="" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="repassnew">Ulangi Kata Sandi Baru</label>
                                <input id="repassnew" type="password" name="" class="form-control" required>
                            </div>
                            <div class="form-group">
                               <button type="submit" class="btn btn-block btn-default">Ganti Kata Sandi</button>
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
      $('#editbtn').click(function(){
        $('#info-name').removeAttr("disabled");
        $('#simpanUpdate').removeClass("d-none");
        $('#formData').attr('action', '{{route('admin.users-cashier.edit-put')}}');
        $(this).addClass("d-none");
      });
    </script>
@endpush