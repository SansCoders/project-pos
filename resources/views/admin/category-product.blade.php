@extends('dashboard-layout.master')

@section('add-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/animate.css/animate.min.css') }}">
<link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 135px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-primary opacity-8"></span>
</div>
<div class="container-fluid mt--6">
    {{-- @if(session()->get('success'))
    <div class="alert alert-success">
        berhasil
    </div>
    @endif --}}
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-transparent border-0">
                    <div class="row">
                        <div class="col-6" style="place-self: center">
                            <h3 class="mb-0">Daftar Kategori Produk</h3>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addCategory"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Total Produk</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @if($categories->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-muted text-center">no record</td>
                                </tr>
                                @endif
                                @foreach ($categories as $index => $category)
                                <tr>
                                    <td>{{ $categories->firstitem() + $index }}</td>
                                    <td class="d-none">{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @php
                                            $count = DB::table('products')->where('category_id', $category->id)->count();
                                            if($count > 0) {
                                               echo $count; 
                                            }else {
                                                echo "0"; 
                                            }
                                        @endphp
                                    </td>
                                    <td class="table-actions d-flex">
                                        <span data-toggle="modal" data-target="#editCategory">
                                            <a href="#!" class="btn btn-white btn-sm ec" data-toggle="tooltip" data-original-title="Ubah kategori">
                                                    <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </span>
                                        <form action="{{ route('admin.changestatus') }}" id="formHpsCategory" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="category">
                                            <input type="hidden" name="id" value="{{$category->id}}">
                                            <span data-toggle="tooltip" data-original-title="Hapus kategori">
                                                <button class="btn btn-danger text-white btn-sm hpsbtn" type="button" data-categname="{{ $category->name }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </span>
                                        </form>
                                      </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end mb-0">
                            {{ $categories->links() }}
                          </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="addCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryLabel">Tambah Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="{{ route('admin.categorys.store') }}" method="POST">
            @csrf
            <label for="namaCategory">Nama Kategori</label>
            <input id="namaCategory" name="name" type="text" class="form-control" required>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="editCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryLabel">Edit Kategori</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="POST" id="feCategory">
            @csrf
            @method('put')
            <input type="hidden" id="idC" name="idC">
            <label for="fcategory">Nama Kategori</label>
            <input id="fcategory" name="name" type="text" class="form-control" required>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
      </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{asset('assets/vendor/bootstrap-notify/bootstrap-notify.min.js')}}"></script>  

<script>
    $(document).ready(function(){
        $('.ec').on('click',function(){
            $tr = $(this).closest('tr');
            let dataC = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            let id = dataC[1];
            let idC = "route('admin.categorys.update',"+dataC[1]+")";
            let url = '{{ route('admin.categorys.update', 'id') }}';
            console.log(url);
            $('#feCategory').attr('action',url)
            $('#idC').val(dataC[1]);
            $('#fcategory').val(dataC[2]);
        });
        function getid(id){
            var zz = '{{ route('admin.categorys.update', '+id+') }}';
            return zz;
        }
    });
    $('.hpsbtn').click(function(){
        var categName = $(this).data('categname');
        Swal.fire({
            title: 'Konfirmasi Penghapusan ?',
            html: "Kategori <strong>"+categName+"</strong> akan dihapus, dan tidak dapat kembali",
            icon: 'warning',
            
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                    'Deleted.',
                    'success'
                    );
                    $('#formHpsCategory').submit();
                }
            });
    });
</script>

@if (count($errors) > 0)
<script>
$.notify({
    message: '@foreach ($errors->all() as $error)'+'<li style="list-style-type: none"><i class="fa fa-times"></i> {{ $error }}</li>'+'@endforeach',
},{
	type: 'danger'
});
</script>
@endif
@if(session()->get('success'))
<script>
    $.notify({
        icon: 'fa fa-check',
	    message: '{{ session()->get('success') }}'
        },{
            element: 'body',
            position: null,
            type: "success",
            allow_dismiss: true,
            newest_on_top: false,
            placement: {
                from: "top",
                align: "right"
            },
            z_index: 1031,
            delay: 5000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            icon_type: 'class',
            template: '<div data-notify="container" class="d-flex col-xs-11 col-sm-3 alert alert-{0}" role="alert" style="width:calc(100% - 30px);">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon" class="mr-2" style="place-self: center;"></span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
            '</div>' 
        });
</script>
@endif

@endpush