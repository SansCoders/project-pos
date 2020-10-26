@extends('dashboard-layout.master')
@section('content')
    <div class="container-fluid">
        <button class="btn btn-success" data-toggle="modal" data-target="#addSales"><i class="fa fa-plus"></i> tambah</button>
        <div class="table-responsive">
            <table class="table table-hover ">
                <thead>
                    <th>no</th>
                    <th>nama</th>
                    <th>action</th>
                </thead>
                <tbody>
                    @foreach ($sales as $user)
                    <tr>
                        <td>{{$user->name}}</td>
                        <td>1</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                        <label class="form-control-label" for="new_username">Username<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="Username pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                        <input id="new_username" type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="new_username">Password<span class="text-danger">*</span> &nbsp;<span data-toggle="tooltip" data-placement="right" title="kata sandi pengguna wajib diisi"><i class="fa fa-question-circle"></i></span> </label>
                        <input id="new_username" type="text" class="form-control" name="username" required>
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