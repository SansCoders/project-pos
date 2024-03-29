@extends('dashboard-layout.master')

@section('add-css')
<link rel="stylesheet" href="{{asset('assets/vendor/sweetalert2/dist/sweetalert2.min.css')}}">
<style>
.modal-dialog {
    max-width: 900px;
}

#preview_img{
  width: 100%;
  min-height: 150px;
  align-self: center;
  margin: 20px 0 20px 0;
}
#preview_img img{
  border-radius: 20px
}

#alligator-turtle {
  object-fit: cover;
  object-position: 100% 0;

  width: 300px;
  height: 337px;
}
</style>
@endsection

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 100px; background-size: cover; background-position: center top;">
    <span class="mask bg-primary opacity-8"></span>
</div>

<div class="container-fluid mt--5">

  <div class="row">
      <div class="col-xl-12">
      @if(session()->has('success'))
        <div class="alert alert-success">
          {{ session()->get('success') }}
        </div>
      @endif
      @if(session()->has('error'))
        <div class="alert alert-danger">
          {{ session()->get('error') }}
        </div>
      @endif
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h5 class="h3 mb-0">Management Produk</h5>
                </div>
                
                <div class="col text-right">
                  @isset($cari)
                  @else    
                  <button class="btn btn-success"  data-toggle="modal" data-target="#addProduct">Tambah Produk</button>
                  @endisset
                </div>
              </div>
          </div>
          <form action="{{route('cashier.products.search')}}" class="mt-2" method="post">
            @csrf
            <div class="d-flex justify-content-center">
              <div class="input-group " style="max-width: 300px">
                <input type="text" class="form-control" name="search" placeholder="Cari Product" aria-label="Cari Product" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-outline-primary" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
          <div class="card-body pl-0 pr-0">
            <div class="table-responsive">
              <table class="table align-items-center">
                <thead class="thead-light">
                  <tr>
                      <th>no</th>
                      <th>Kode Produk</th>
                      <th>Produk</th>
                      <th>Harga Produk</th>
                      <th>Action</th>
                  </tr>
                </thead>
                <tbody class="list">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($products as $index => $product)
                      @if ($product->product_status == "show")
                        <tr>
                          <th role="row">{{ $i++ }}</th>
                          <td>{{ $product->kodebrg }}</td>
                          <td class="d-flex align-items-center">
                            <img src="{{ asset($product->img) }}" class="rounded-circle avatar" style="max-width: 50px" alt="">
                            <span class="ml-2">{{ $product->nama_product }}</span>
                          </td>
                          <td>@currency($product->price)</td>
                          <td>
                            <a class="btn btn-primary" href="/cashier/product_info/{{ $product->id }}">Edit</a>
                            <form action="{{ route('cashier.products.destroyTemp',$product->id) }}" id="fdelet{{$product->id}}" method="POST" style="display: contents">
                              @csrf
                              @method('PUT')
                              <button class="btn btn-icon btn-danger hpsbtn" data-idp="{{$product->id}}" type="button">
                                  <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                  <span class="btn-inner--text">Hapus</span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer">
            {{$products->links()}}
          </div>
        </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="infoProduct" tabindex="-1" role="dialog" aria-labelledby="infoProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoProductLabel">Info Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead class="thead-light">
                    <tr>
                        <th>no</th>
                    </tr>
                </thead>
                <tbody class="list" id="tbody">

                </tbody>
            </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary">edit</button>
        </div>
      </div>
    </div>
  </div>

  @isset($cari)
  @else    
  <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductLabel">Tambah Produk</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body row">
            <div class="col-lg-6 mb-4 d-flex flex-column">
              <h1 class="h2 text-muted mb-3">Preview</h1>
              <div class="card" style="border: 1px solid grey">
                <div class="card-body">
                  <div id="preview_img" class="text-center"></div>
                  <h2 id="preview_pNama" class="mb-1 text-muted">Nama Produk</h2>
                  <h2 class="text-dark" id="preview_pPrice">IDR 0,00</h2>
                  <p id="preview_description"></p>
                  <div class="text-right">
                    <button class="btn btn-success btn-icon"><i class="ni ni-basket"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
                <form method="POST" action="{{route('cashier.products.store')}}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group">
                    <label for="pKode" class="form-control-label">Kode Produk <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
                    <input id="pKode" name="pKode" value="{{old('pKode')}}" type="text" class="form-control" onkeyup="ppKode();" required>
                    @error('pKode')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="pNama" class="form-control-label">Nama Produk <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
                    <input id="pNama" name="pNama" value="{{old('pNama')}}" type="text" class="form-control" onkeyup="ppNama();" required>
                    @error('pNama')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="pCategory" class="form-control-label">Kategori <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
                    <select id="pCategory" name="pCategory" class="form-control" required>
                      @foreach ($categories as $category)
                        <option value="{{$category->id}}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="row">
                    <div class="form-group col-6">
                        <label for="pStok" class="form-control-label">Stok <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
                        <div class="input-group input-group-merge">
                          <input type="text" value="{{old('pStok')}}" class="form-control justnumber" name="pStok" placeholder="0" required>
                          <select name="pUnit" id="pUnit" class="form-control">
                            @if (count($units) < 1)
                              <option disabled>no record</option>
                            @endif
                            @foreach ($units as $u)
                              <option value="{{ $u->id }}">{{ $u->unit }}</option>
                            @endforeach
                          </select>
                        </div>
                        @error('pStok')
                          <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    <div class="form-group col-6">
                      <label for="pPrice" class="form-control-label">Harga <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
                      <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">IDR</span>
                          </div>
                          <input id="pPrice" type="text" class="form-control text-right justnumber" name="pPrice" onkeyup="ppPrice()" placeholder="0" aria-label="0" aria-describedby="basic-addon1" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="imgproduct" class="form-control-label">Gambar</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="imgproduct" name="imgproduct" lang="id" accept="image/*">
                      <label class="custom-file-label" for="imgproduct">Select file</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="pDescription" class="form-control-label">Deskripsi</label>
                    <textarea id="pDescription" name="pDescription"></textarea>
                  </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          </form>
        </div>
      </div>
  </div>
  @endisset
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
<script>
  $(document).ready(function(){
    ClassicEditor
        .create( document.querySelector( '#pDescription',{
          removePlugins: 'toolbar',
        } ) )
        .catch( error => {
            console.error( error );
        } );
  });
  $('.hpsbtn').click(function(){
    var idp = $(this).data('idp');
    Swal.fire({
      title: 'Yakin ingin menghapus product tersebut?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: `Hapus`,
      icon: 'warning',
      denyButtonText: `Batal`,
    }).then((result) => {
      if(result.isConfirmed){
        $('#fdelet'+idp).submit();
        Swal.fire({
          icon: 'success',
          showConfirmButton: false,
          timer: 1500,
          title: 'berhasil dihapus'
        });
        setTimeout(function(){
            location.reload(); 
        }, 1800);
      }
    });
  });

  $(".infop").click(function(e){
    e.preventDefault();
    var dataid_p = $(this).data('product_id');
    console.log(dataid_p);
    $.ajax({
      dataType: 'json',
      type: "GET",
      url: '/cashier/getinfo_product/'+dataid_p,
      success: function(result){
            console.log(result);
            var res='';
            $.each (result, function (key, value) {
            res +=
            '<tr>'+
                '<td>'+value.id+'</td>'+
                '<td>'+value.nama_product+'</td>'+
           '</tr>';

            });

            $('tbody').html(res);
        }
    });
  });

  $(".priceProduct").each(function() {
      $(this).html('RP ' +$(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
  });
  $(document).on('keydown', '#pKode', function(e) {
      $(this).val($(this).val().toUpperCase());
    if (e.keyCode == 32) return false;
  });
  function ppNama()
  {
    $("#preview_pNama").html($("#pNama").val());
    $('#pNama').change(function(){
      if($("#preview_pNama").text().length === 0){
        $("#preview_pNama").text("Nama Produk");
      }
    });
  }
  function ppPrice()
  {
    let vP = $("#pPrice").val();
    $("#preview_pPrice").html('RP ' +vP.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    vP.change(function(){
      if(vP.length == 0){
        $("#preview_pPrice").text('RP ' + 0);
      }
    });
  }
  function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
          $('#preview_img').html('<img src="' +e.target.result+'" class="img-fluid" />');
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
    }

    $("#imgproduct").change(function() {
      readURL(this);
    });
</script>
@endpush