@extends('dashboard-layout.master')
@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 135px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-primary opacity-8"></span>
</div>
<div class="container-fluid mt--6">
        <h1 class="h3 text-muted">Manajemen Pengguna Sales</h1>
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{session()->get('success')}}
            </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center border-0">
                <strong>Sales</strong>
                <button class="btn btn-success" data-toggle="modal" data-target="#addSales"><i class="fa fa-plus"></i> tambah</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead>
                        <th>no</th>
                        <th>username</th>
                        <th>nama</th>
                        <th>action</th>
                    </thead>
                    <tbody>
                        @foreach ($sales as $index => $user)
                        <tr class="data-row">
                        <td>{{ $sales->firstitem() + $index }}</td>
                        <td>{{$user->username}}</td>
                        <td><a href="{{route('user.profile',$user->id)}}" class="text-default">{{$user->name}}</a></td>
                            <td class="table-actions d-flex">
                                <a href="{{route('admin.users-sales.edit',$user->id)}}" class="btn btn-white btn-sm" data-toggle="tooltip"  data-original-title="Edit Pengguna">
                                    <i class="fas fa-user-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.changestatususer') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="sales">
                                    <input type="hidden" name="iduser" value="{{$user->id}}">
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                {{-- <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Hapus Pengguna">
                                  <i class="fas fa-trash"></i> Hapus
                                </a> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{$sales->links()}}
            </div>
        </div>
    </div>


    <div class="modal fade" id="addSales" tabindex="-1" role="dialog" aria-labelledby="addSalesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addSalesLabel">Tambah Sales</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <form method="POST" action="{{route('admin.users-sales.store')}}">
                    @csrf
                    <div class="form-group">
                        <label class="form-control-label" for="new_name">Nama<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="Username pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                        <input id="new_name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="new_username">Username<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="Username pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                        <input id="new_username" type="text" class="form-control" name="username" value="{{ old('username') }}"  required>
                        @error('username')
                            <span class="text-danger">{{$message}}</span>   
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="new_password">Password<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="kata sandi pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                        <input id="new_password" type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="new_phone">Phone<span class="text-danger"></label>
                        <input id="new_phone" type="number" class="form-control" name="new_phone">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="new_alamat">Alamat<span class="text-danger"></label>
                        <textarea id="new_alamat" rows="3" name="new_alamat" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </form>
          </div>
        </div>
    </div>


    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserLabel">Edit Sales</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                  <form method="POST" action="#">
                    @csrf
                    <div class="form-group">
                        <label class="form-control-label" for="new_name">Nama<span class="text-danger">*</span> </label>
                        <input id="edit_name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.ec').on('click',function(){
            $(this).addClass('edit-user-trigger-clicked');
            console.log($(this).data('user-id'));
        });
    </script>
@endpush