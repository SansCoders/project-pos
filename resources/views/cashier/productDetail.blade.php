@extends('dashboard-layout.master')

@section('content')
<div class="header pb-6 d-flex align-items-center" style="min-height: 100px; background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-primary opacity-8"></span>
</div>

<div class="container-fluid mt--5">
<div class="row">
      <div class="col-xl-12">
          <div class="card">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h5 class="h3 mb-0">Edit Produk</h5>
                </div>
                
                <div class="col text-right">
                  <a class="btn btn-primary"  href="{{url()->previous()}}">Kembali</a>
                </div>
              </div>
            </div>
            <div class="card-body">
            <div class="modal-body row">
            {{-- <div id="netframe">
              <iframe height="100%" width="100%" class="netframe" src="pullsite.php" id="main_frame"></iframe>
          </div>
          Enter A URL:
          <input type="text" name="url" id="url">
          <input type="button" value="load" id="load">
          <br><br>   --}}
            <div class="col-lg-6 mb-4 d-flex flex-column">
              <h1 class="h2 text-muted mb-3">Preview</h1>
              <div class="card" style="border: 1px solid grey">
                <div class="card-body">
                  <div id="preview_img" class="text-center"></div>
                  <h2 id="preview_pNama" class="mb-1 text-muted">Nama Produk</h2>
                  {{-- <h6 class="text-muted" id="preview_pKode">Kode Produk</h6> --}}
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
                    <input id="pKode" name="pKode" value="{{old('pKode')}}" type="text" class="form-control" onkeyup="ppKode();" required>{{$getProduct->nama_product}}
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
                        <label for="pStok" class="form-control-label">Stok</label>
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control justnumber" name="pStok" placeholder="0">
                          <select name="pUnit" id="pUnit" class="form-control">
                            @if ($units->isEmpty())
                            <option disabled>no record</option>
                            @endif
                            @foreach ($units as $u)
                              <option value="{{ $u->id }}">{{ $u->unit }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    <div class="form-group col-6">
                      <label for="pPrice" class="form-control-label">Harga</label>
                      <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                              <span class="input-group-text" id="basic-addon1">IDR</span>
                          </div>
                          <input id="pPrice" type="text" class="form-control text-right justnumber" name="pPrice" onkeyup="ppPrice()" placeholder="0" aria-label="0" aria-describedby="basic-addon1">
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
            </div>
          </div>
        </div>

@endsection

@push('scripts')
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

  $(".priceProduct").each(function() {
      $(this).html('RP ' +$(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
  });
  $(document).on('keydown', '#pKode', function(e) {
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
  // $('.justnumber').keyup(function(event) {
  //    // skip for arrow keys
  //     if(event.which >= 37 && event.which <= 40) return;

  //     // format number
  //     $(this).val(function(index, value) {
  //       return value
  //       .replace(/\D/g, "")
  //       .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
  //       ;
  //     });
  // });
  function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
          $('#preview_img').html('<img src="' +e.target.result+'" class="img-fluid" />');
          // $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]); // convert to base64 string
      }
    }

    $("#imgproduct").change(function() {
      readURL(this);
    });
</script>
@endpush