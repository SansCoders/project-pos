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
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">Change Password</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false">Bank Account</a>
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
                            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabel-icons-text-4-tab">
                              <div class="mb-3">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#bankInfo">
                                  tambah
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="bankInfo" tabindex="-1" aria-labelledby="bankInfoLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title" id="bankInfoLabel">Tambah Data Bank</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                      </div>
                                      <form action="add_bank_info" method="post">
                                        @csrf
                                        <div class="modal-body">
                                          <div class="form-group">
                                            <label>Jenis Bank</label>
                                              <div class="input-group mb-3">
                                                <input type="text" name="bank_name" class="form-control" placeholder="BCA, BNI, BRI, etc" required>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                            <label>atas nama</label>
                                              <div class="input-group mb-3">
                                                <input type="text" name="owner_acc" class="form-control" placeholder="nama pemilik bank" required>
                                              </div>
                                          </div>
                                          <div class="form-group">
                                            <label>nomor rekening</label>
                                              <div class="input-group mb-3">
                                                <input type="text" name="rekening_number" class="form-control" placeholder="nomor rekening" required>
                                              </div>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="reset" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                          <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <table class="table">
                                <tr>
                                  <td>no</td>
                                  <td>Bank</td>
                                  <td>Atas Nama</td>
                                  <td>Details</td>
                                </tr>
                                @foreach (App\BankInfo::getAllBankInfos() as $index => $bankInfo)
                                    <tr>
                                      <td>{{$index+1}}</td>
                                      <td>{{$bankInfo->bank_name}}</td>
                                      <td>{{$bankInfo->rekening_owner_name}}</td>
                                      <td>
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#bankDetails{{$index}}">
                                          details
                                        </button>
                                      </td>
                                    </tr>
                                    
                                  </div>
                                  {{-- <!-- Modal -->
                                  <div class="modal fade" id="bankDetails{{$index}}" tabindex="-1" aria-labelledby="bankDetails{{$index}}Label" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title" id="bankDetails{{$index}}Label">Details</h5>
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </div>
                                        <div class="modal-body">
                                          <div class="">
                                            <table class="table">
                                              <thead>
                                                <tr>
                                                  <th>Bank</th>
                                                  <th>Nomor Rekening</th>
                                                  <th>Atas Nama</th>
                                                  <th>Aksi</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td>{{$bankInfo}}</td>
                                                  <td></td>
                                                  <td></td>
                                                  <td>#</td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </div>
                                          @if($bankInfo->qr_code == "" || $bankInfo->qr_code == "#")
                                            <form action="" method="post">
                                              @csrf
                                              <input type="hidden" name="biId" value="{{$bankInfo->id}}">
                                              <button class="btn btn-success">generate qr</button>
                                            </form>
                                          @else
                                          <form action="" method="post">
                                            @csrf
                                            <input type="hidden" name="biId" value="{{$bankInfo->id}}">
                                            <button class="btn btn-success btn-sm">regenerate qr</button>
                                          </form>
                                          @endif
                                        </div>
                                      </div>
                                    </div>
                                  </div> --}}
                                @endforeach
                              </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach (App\BankInfo::getAllBankInfos() as $index => $bankInfo)
    <!-- Modal -->
    <div class="modal fade" id="bankDetails{{$index}}" tabindex="-1" aria-labelledby="bankDetails{{$index}}Label" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="bankDetails{{$index}}Label">Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Bank</th>
                    <th>Nomor Rekening</th>
                    <th>Atas Nama</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{$bankInfo->bank_name}}</td>
                    <td>{{$bankInfo->rekening_number}}</td>
                    <td>{{$bankInfo->rekening_owner_name}}</td>
                    <td>
                      <div class="">
                        <form action="{{route('admin.regenerate-qr',)}}" method="get">
                          <input type="hidden" name="dBid" value="{{$bankInfo->id}}">
                          <button type="submit" class="btn btn-sm btn-info">ubah</button>
                        </form>
                        <form action="{{route('delete.bank')}}" method="post">
                          @csrf
                          @method('delete')
                          <input type="hidden" name="id" value="{{$bankInfo->id}}">
                          <button type="submit" class="btn btn-sm btn-outline-danger">hapus</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            @if($bankInfo->qr_code == "" || $bankInfo->qr_code == "#")
            <div class="alert alert-danger">
              <strong>QR Code Belum Di Generate</strong>
            </div>
              <form action="" method="post">
                @csrf
                <input type="hidden" name="biId" value="{{$bankInfo->id}}">
                <button class="btn btn-success">generate qr</button>
              </form>
            @else
            <img src="{{asset($bankInfo->qr_code)}}" alt="">
            {{-- <form action="" method="post">
              @csrf
              <input type="hidden" name="biId" value="{{$bankInfo->id}}">
              <button class="btn btn-success btn-sm">regenerate qr</button>
            </form> --}}
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
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