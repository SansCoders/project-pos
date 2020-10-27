@extends('dashboard-layout.master')

@section('content')
<div class="container-fluid">
  <button class="btn btn-success btn-icon btn-sm"  data-toggle="modal" data-target="#addProduct"><i class="fa fa-plus"></i></button>
  @foreach ($products as $product)
      {{ $product->nama_product }}
      @isset($product->category->name)
      {{ $product->category->name }}
      @endisset
      <img src="{{ $product->img }}" alt="" />    
  @endforeach

  <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addProductLabel">Tambah Produk</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <form method="POST" action="">
              @csrf
              <label for="fcategory">Nama Kategori</label>
              <input id="fcategory" name="ename" type="text" class="form-control" required>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
          </form>
        </div>
      </div>
  </div>
</div>
@endsection