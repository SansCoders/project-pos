@extends('dashboard-layout.master')

@section('add-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
@endsection

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 135px; background-image: url(../assets/img/theme/bg.jpg); background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-primary opacity-8"></span>
    </div>
    <div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header bg-transparent border-0">
                    <div class="row">
                        <div class="col-6" style="place-self: center">
                            <h3 class="mb-0">Daftar Units</h3>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addUnit"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody> 
                                @if($units->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-muted text-center">no record</td>
                                </tr>
                                @endif
                                @foreach ($units as $index => $unit)
                                <tr>
                                    <td>{{ $units->firstitem() + $index }}</td>
                                    <td class="d-none">{{ $unit->id }}</td>
                                    <td>{{ $unit->unit }}</td>
                                    <td class="table-actions d-flex">
                                        <span data-toggle="modal" data-target="#editUnit">
                                            <a href="#!" class="btn btn-white text-black btn-sm eu" data-toggle="tooltip" data-original-title="Ubah">
                                                    <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </span>
                                        <form action="{{ route('admin.changestatus') }}" id="formHpsUnit" method="POST">
                                            @csrf
                                            <input type="hidden" name="type" value="unit">
                                            <input type="hidden" name="id" value="{{$unit->id}}">
                                            <button class="btn btn-danger text-white btn-sm hpsbtn" type="button" data-unitname="{{ $unit->unit }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
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
                            {{ $units->links() }}
                          </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addUnit" tabindex="-1" role="dialog" aria-labelledby="addUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUnitLabel">Tambah Unit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form action="{{ route('admin.unit.store') }}" method="POST">
            @csrf
            <label for="namaUnit">Nama Unit</label>
            <input id="namaUnit" name="unit" type="text" class="form-control" required>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
        </form>
      </div>
    </div>
</div>

<div class="modal fade" id="editUnit" tabindex="-1" role="dialog" aria-labelledby="editUnitLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUnitLabel">Edit Unit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form method="POST" id="feUnit">
            @csrf
            @method('put')
            <input type="hidden" id="idU" name="idU">
            <label for="fUnit">Nama Unit</label>
            <input id="fUnit" name="name" type="text" class="form-control" required>
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
        $('.eu').on('click',function(){
            $tr = $(this).closest('tr');
            let dataUP = $tr.children("td").map(function(){
                return $(this).text();
            }).get();
            let id = dataUP[1];
            let url = '{{ route('admin.units.update', 'id') }}';
            console.log(url);
            $('#feUnit').attr('action',url)
            $('#idU').val(dataUP[1]);
            $('#fUnit').val(dataUP[2]);
        });
        function getid(id){
            var zz = '{{ route('admin.units.update', '+id+') }}';
            return zz;
        }
    });

    $('.hpsbtn').click(function(){
        var unitName = $(this).data('unitname');
        Swal.fire({
            title: 'Konfirmasi Penghapusan Unit ?',
            html: "unit <strong>"+unitName+"</strong> akan dihapus, dan tidak dapat kembali",
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
                    $('#formHpsUnit').submit();
                }
            });
    });

</script>

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