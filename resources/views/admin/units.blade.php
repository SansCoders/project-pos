@extends('dashboard-layout.master')

@section('content')
<div class="container-fluid mt-3">
@if(session()->get('success'))
    <div class="alert alert-success">
        s
    </div>
@endif
    <div class="row">
        <div class="col">
            <div class="card shadow-sm">
                <div class="card-header  border-0">
                    <div class="row">
                        <div class="col-6" style="place-self: center">
                            <h3 class="mb-0">Daftar Unit</h3>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-light rounded-circle" type="button"><i class="fa fa-search"></i></button>
                            <button class="btn btn-success" data-toggle="modal" data-target="#addUnit"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Unit</th>
                                    <th></th>
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
                                    <td class="table-actions">
                                        <span data-toggle="modal" data-target="#editUnit">
                                            <a href="#!" class="table-action text-light eu" data-toggle="tooltip" data-original-title="Ubah">
                                                    <i class="fas fa-edit"></i>
                                            </a>
                                        </span>
                                        <a href="#!" class="table-action table-action-delete text-light" data-toggle="tooltip" data-original-title="Hapus">
                                          <i class="fas fa-trash"></i>
                                        </a>
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
</script>
@endpush