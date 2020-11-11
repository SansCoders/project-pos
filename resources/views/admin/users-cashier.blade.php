@extends('dashboard-layout.master')
@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 135px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-primary opacity-8"></span>
    <div class="container-fluid">
      <div class="header-body">
        <!-- Card stats -->
        <div class="row py-4">
          <div class="col-xl-6 col-md-6">
            <div class="card card-stats">
              <!-- Card body -->
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">Total Kasir</h5>
                  <span class="h2 font-weight-bold mb-0">{{ $cashier->count() }}</span>
                  </div>
                  <div class="col-auto">
                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                      <i class="fa fa-cash-register"></i>
                    </div>
                  </div>
                </div>
                <p class="mt-3 mb-0 text-sm">
                  <div class="avatar-group">
                    @foreach ($cashier->take(5) as $user)
                      <a href="#" class="avatar avatar-sm rounded-circle" style="z-index: 0" data-toggle="tooltip" data-original-title="{{$user->name}}">
                        <img alt="Image placeholder" src="../../assets/img/theme/team-1.jpg">
                      </a>
                    @endforeach
                  </div>
                  {{-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                  <span class="text-nowrap">Since last month</span> --}}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="container-fluid mt--6">
   @if(session()->get('success'))
            <div class="alert alert-success">
                asd &times;
            </div>
            @endif
  <div class="card">
                <div class="card-header text-right">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addCashier"><i class="fa fa-plus"></i> tambah</button>
                </div>
                <div class="card-body pl-0 pr-0">
                    <div class="table-responsive">
                        <table class="table table-hover ">
                            <thead>
                                <th>no</th>
                                <th>nama</th>
                                <th>action</th>
                            </thead>
                            <tbody>
                                @foreach ($cashier as $index => $user)
                                <tr class="data-row">
                                <td>{{ $cashier->firstitem() + $index }}</td>
                                <td><a href="{{route('user.profile',$user->id)}}" class="text-default">{{$user->name}}</a></td>
                                    <td class="table-actions">
                                        <span data-toggle="modal" data-target="#editUser">
                                            <a href="#!" class="btn btn-white" data-toggle="tooltip"  data-user-id="{{$user->id}}" data-original-title="Edit Pengguna">
                                                    <i class="fas fa-user-edit"></i> Edit
                                            </a>
                                        </span>
                                        <a href="#!" class="btn btn-danger" data-toggle="tooltip" data-original-title="Hapus Pengguna">
                                          <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
  </div>
</div>


<div class="modal fade" id="addCashier" tabindex="-1" role="dialog" aria-labelledby="addCashierLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCashierLabel">Tambah Kasir</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <form method="POST" action="{{route('admin.users-cashier.store')}}">
              @csrf
              <div class="form-group">
                  <label class="form-control-label" for="new_name">Nama<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="Username pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                  <input id="new_name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
              </div>
              <div class="form-group">
                  <label class="form-control-label" for="new_username">Username<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="Username pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                  <input id="new_username" type="text" class="form-control" name="username" value="{{ old('username') }}"  required>
              </div>
              <div class="form-group">
                  <label class="form-control-label" for="new_password">Password<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="kata sandi pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                  <input id="new_password" type="password" class="form-control" name="password" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success">Tambah</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection