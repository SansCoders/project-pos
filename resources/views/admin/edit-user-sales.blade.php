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
                            <img src="{{ asset($dataUser->profile->photo)}}" class="rounded-circle">
                        </a>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                          <div class="col">
                            <div class="card-profile-stats d-flex justify-content-center">
                              @php
                                  $countTotalOrder = App\Receipts_Transaction::where('user_id',$dataUser->id)->where('order_via',3)->get();
                              @endphp
                              <div>
                                <span class="heading">{{$countTotalOrder->count()}}</span>
                                <span class="description">Total Orders</span>
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
                <div class="card shadow-none">
                    {{-- <div class="card-header h3">Edit User Info</div> --}}
                    <div class="card-header">
                      <ul class="nav nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Profile</a>
                        </li>
                          {{-- <li class="nav-item">
                              <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Summary</a>
                          </li> --}}
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
                          <form action="{{ route('admin.users-sales.edit-put') }}" method="post" id="formData">
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
                                  <div class="row">
                                      <div class="col-lg-6">
                                          <div class="form-group">
                                              <label for="info-phone" class="form-control-label">Hp</label>
                                              <input class="form-control" type="number" name="info_phone" value="{{ $dataUser->phone }}" id="info-phone" disabled required>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <hr class="my-4">
                              <div class="pl-lg-4">
                                  <div class="form-group">
                                      <label for="infoalamat" class="form-control-label">Alamat</label>
                                      <textarea id="infoalamat" rows="4" class="form-control" name="info_alamat" disabled>{{ $dataUser->address }}</textarea>
                                  </div>
                              </div>   
                            <div id="btnaction" class="text-right">
                                <button id="editbtn" type="button" class="btn btn-sm btn-light btn-icon">edit</button>
                                <button id="simpanUpdate" type="submit" class="btn btn-sm btn-success btn-icon d-none">Update</button>
                            </div>
                          </form>   
                        </div>
                        {{-- <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                        ...
                        </div> --}}
                        <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                          <form action="{{route('admin.changepass')}}" method="POST">
                            @csrf
                            @method("PUT")
                            <input id="iduser" type="hidden" name="iduser" value="{{$dataUser->id}}" required>
                            <input id="typeuser" type="hidden" name="typeUser" value="sales" required>
                            <div class="form-group">
                                <label for="passold">Kata Sandi Lama</label>
                                <input id="passold" type="password" name="oldpassword" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="passnew">Kata Sandi Baru</label>
                                <input id="passnew" type="password" name="newpass" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="repassnew">Ulangi Kata Sandi Baru</label>
                                <input id="repassnew" type="password" name="newpass2" class="form-control" required>
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
        $('#info-phone').removeAttr("disabled");
        $('#infoalamat').removeAttr("disabled");
        $('#simpanUpdate').removeClass("d-none");
        $('#formData').attr('action', '{{route('admin.users-sales.edit-put')}}');
        $(this).addClass("d-none");
      });
    </script>
@endpush