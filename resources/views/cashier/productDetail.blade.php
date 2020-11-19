@extends('dashboard-layout.master')
@include('dashboard-layout.footer')
@section('content')
<div class="container-fluid mt-4">
  <div class="alert alert-default text-white">
    <i class="fa fa-exclamation-triangle"></i>&nbsp;<strong>Untuk perubahan unit dan stok tidak bisa dilakukan</strong>
  </div>
  <a class="btn btn-primary mb-2"  href="{{route('cashier.products')}}"><i class="fa fa-long-arrow-alt-left"></i></a>
  <div class="d-flex justify-content-center">
      <div class="card shadow-sm">
        <div class="card-header bg-transparent">
          Edit Product
        </div>
        <div class="card-body">
          <form method="POST" action="{{route('cashier.products.update')}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <input type="hidden" id="id" name="id" value="{{$product->id}}">
              <label for="pKode" class="form-control-label">Kode Produk <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
              <input value="{{$product->kodebrg}}" type="text" class="form-control" disabled required>
            </div>
            <div class="form-group">
              <label for="pNama" class="form-control-label">Nama Produk <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
              <input id="pNama" name="pNama" value="{{$product->nama_product}}" type="text" class="form-control" onkeyup="ppNama();" required>
              @error('pNama')
                  <div class="alert alert-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="form-group">
              <label for="pCategory" class="form-control-label">Kategori <span class="text-danger" data-toggle="tooltip" data-placement="right" title="Harus Diisi">*</span></label>
              <select id="pCategory" name="pCategory" class="form-control" required>
                @foreach ($categories as $category)
                  @if ($category->id == $product->category_id)
                  <option value="{{$category->id}}" selected>{{ $category->name }}</option>
                  @else
                  <option value="{{$category->id}}">{{ $category->name }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="pPrice" class="form-control-label">Harga</label>
              <div class="input-group input-group-merge">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="basic-addon1">IDR</span>
                </div>
                <input id="pPrice" value="{{ $product->price }}" type="text" class="form-control text-right justnumber" name="pPrice" onkeyup="ppPrice()" placeholder="0" aria-label="0" aria-describedby="basic-addon1">
              </div>
            </div>
            <div class="form-group">
              <label for="imgproduct" class="form-control-label">Gambar</label>
              <div class="custom-file">
                <input type="file"  value="{{ $product->description }}"  class="custom-file-input" id="imgproduct" name="imgproduct" lang="id" accept="image/*">
                <label class="custom-file-label" for="imgproduct">Select file</label>
              </div>
            </div>
            <div class="form-group">
              <label for="pDescription" class="form-control-label">Deskripsi</label>
              <textarea id="pDescription" name="pDescription">{{ $product->description }}</textarea>
            </div>
          
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary btn-block">Update</button>
        </div>
      </form>
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