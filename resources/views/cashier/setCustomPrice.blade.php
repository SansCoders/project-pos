@extends('dashboard-layout.master')

@section('add-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/animate.css/animate.min.css') }}">
@endsection

@section('header-content')
<div class="bg-primary p-3" style="width: 100%;z-index:1">
    <a href="{{route('cashier.customprice')}}" class="btn btn-info ml-2"><i class="fa fa-arrow-alt-circle-left"></i> kembali</a>
</div>
    <div class="header py-7">
      <span class="mask bg-primary opacity-1"></span>
        <div class="container-fluid d-flex align-items-center justify-content-center">
            <img src="{{asset($sales->profile->photo)}}" style="border-radius: 10px; max-width:200px" alt="">
            <div class="card bg-transparent shadow-none ml-2">
              <div class="card-body">
                <h1 class="mb-5">{{ $sales->name }}</h1>
                <span class="badge badge-default badge-lg">
                  <i class="fa fa-phone-alt"></i>
                  <strong>{{ $sales->phone }}</strong>
                </span>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>no</th>
                            <th colspan="2" class="text-center">keterangan</th>
                            <th>harga normal</th>
                            <th>harga khusus</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $products = App\Product::where('product_status', 'show')->paginate(5);
                        @endphp
                        @foreach ($products as $index => $item)
                            @php
                               $priceCustom = App\Prices_Custom::where('product_id',$item->id)
                               ->where('user_id',$sales->id)->where('user_type','user')->first();
                            @endphp
                            <tr>
                                <td>{{$products->firstitem() + $index}}</td>
                                <td>{{$item->kodebrg}}</td>
                                <td><b>{{$item->nama_product}}</b></td>
                                <td>@currency($item->price)</td>
                                <td>
                                    @if($priceCustom != null)
                                        @currency($priceCustom->prices_c)
                                   @endif
                                </td>
                                <td>
                                  @if($priceCustom != null)
                                    <a href="#editHarga" class="editPrice" data-pcid="{{$priceCustom->id}}" data-toggle="modal" >
                                      <span class="badge badge-lg badge-warning">ubah harga</span>
                                    </a>
                                  @else
                                    <a href="#setHarga" class="setPrice" data-cp="{{$item->id}}" data-toggle="modal" >
                                      <span class="badge badge-lg badge-primary">set harga</span>
                                    </a>
                                  @endif
                                </td>
                            </tr>
                        @endforeach
                        {{$products->links()}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="setHarga" tabindex="-1" role="dialog" aria-labelledby="setHargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="setHargaLabel">Atur Harga Khusus</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center" id="formSP">
                <h1 id="nproduct"></h1>

                <div class="mt-2 text-left d-flex flex-column">
                  <span class="text-muted">harga normal  : <strong id="pnormal"></strong></span>
                  <form action="{{ route('cashier.customprice.confirm',$sales->id) }}" class="py-3" method="post">
                    @csrf
                    <input type="hidden" name="productid" id="pid">
                    <span class="text-muted">harga khusus  : <input type="number" min="1" class="form-control" name="usHargaKhusus" id=""></span>
                  
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success">Simpan</button>
            </div>
          </form>
          </div>
        </div>
      </div>

    <div class="modal fade" id="editHarga" tabindex="-1" role="dialog" aria-labelledby="editHargaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editHargaLabel">Edit Harga Khusus</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body text-center" id="formSP">
                <h1 id="nproduct2"></h1>
                <div class="mt-2 text-left d-flex flex-column">
                  <span class="text-muted">harga normal  : <strong id="pnormal2"></strong></span>
                  <form action="{{ route('cashier.customprice.edit',$sales->id) }}" class="py-3" method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" name="productid" id="pid2">
                    <input type="hidden" name="e_pcid" id="pcid">
                    <span class="text-muted">harga khusus  : <input type="number" min="1" class="form-control" name="usHargaKhususEdit" id="editHargaKhusus"></span>
                  
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between flex-row-reverse">
              <button type="submit" class="btn btn-success">Simpan</button>
            </form>
          <form action="{{ route('cashier.customprice.delete',$sales->id) }}" method="POST">
              @csrf
              @method('delete')
              <input type="hidden" name="customPid" id="customPid">
              <button type="submit" class="btn btn-secondary">Jadikan Harga Normal</button>
            </form>
            </div>
          </div>
        </div>
      </div>
@endsection

@push('scripts')
<script src="{{asset('assets/vendor/bootstrap-notify/bootstrap-notify.min.js')}}"></script>  
<script>
  $('.setPrice').click(function(e){
    e.preventDefault();
    var idproduct = $(this).data('cp');
    $.ajax({
      url : "/api/p/find",
      method:"post",
      data : {"_token":"{{ csrf_token() }}","idproduct" : idproduct},
      success: function(resp){
        $('#nproduct').text(resp.nama_product);
        $('#pid').val(resp.id);
        var hn = parseFloat(Number(resp.price));
        $('#pnormal').text(resp.price);
      }
    });
  });

  $('.editPrice').click(function(e){
    e.preventDefault();
    var pcid = $(this).data('pcid');
    $.ajax({
      url : "/api/p/cfind",
      method:"post",
      data : {"_token":"{{ csrf_token() }}","pcid" : pcid},
      success: function(resp){
        $('#nproduct2').text(resp[1].nama_product);
        $('#pcid').val(resp[0].id);
        $('#customPid').val(resp[0].id);
        $('#pid2').val(resp[1].id);
        var hn2 = parseFloat(Number(resp[1].price));
        var cproduct = parseFloat(Number(resp[0].prices_c));
        $('#pnormal2').text(hn2);
        $('#editHargaKhusus').val(cproduct);
      }
    });
  });
</script>   

@if(session()->get('message'))
<script>
    $.notify({
        icon: 'fa fa-check',
	    message: '{{ session()->get('message') }}'
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