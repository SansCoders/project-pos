@extends('dashboard-layout.master')

@section('content')
    <div class="container-fluid mt-5">
        {{-- <div class="search">
            <form action="">
                <input type="text" class="form-control form-control-muted col-7" placeholder="search product">
            </form>
        </div> --}}
        <div class="card shadow-none">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="mb-0">Manajeman Stock</h3>
                    <div class="">
                        <form action="" method="GET" class="form-inline">
                          <input type="text" name="cari" class="form-control" placeholder="cari" id="">
                          <button type="submit" class="btn btn-info btn-icon-only"><i class="fa fa-search"></i></button>
                        </form>
                        {{-- <a data-toggle="modal" href="#addStock" role="button" class="btn btn-sm btn-success btn-round btn-icon" >
                            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                            <span class="btn-inner--text">Tambah</span>
                        </a>
                        <a href="#" role="button" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Filter product">
                            <span class="btn-inner--icon"><i class="fas fa-sliders-h"></i></span>
                            <span class="btn-inner--text">Filter</span>
                        </a> --}}
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-items-center table-flush table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>no</th>
                            <th>Produk</th>
                            <th>Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($products as $index => $product)
                          @if ($product->product_status != 'hide')
                            <tr>
                                <td>{{$i++}}</td>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="avatar rounded-circle mr-3">
                                          <img alt="Image placeholder" style="height: inherit" src="{{ asset($product->img) }}" alt="gambar {{ $product->nama_product }}">
                                        </a>
                                        <div class="media-body">
                                          <span class="name mb-0 text-sm">{{$product->nama_product}}</span>
                                          <span class="badge badge-pill badge-info">{{$product->category->name}}</span>
                                        </div>
                                    </div>
                                </th>
                                <td>@isset($product->stocks)
                                      @if ($product->stocks->stock < 1)
                                      <span class="text-danger">{{ $product->stocks->stock }} {{ $product->unit->unit }}</span>
                                      @else    
                                      <span>{{ $product->stocks->stock }} {{ $product->unit->unit }}</span>
                                      @endif
                                    @else
                                        <span class="text-danger">stok habis</span>
                                    @endisset</td>
                                <td class="table-actions">
                                <a href="{{ route('stock.add.process',$product->id) }}" class="btn btn-sm btn-primary text-white" data-toggle="tooltip" data-original-title="tambah stock">
                                        <i class="fas fa-plus-square"></i>
                                    </a>
                                </td>
                            </tr>
                          @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-0 align-self-center">
              {{ $products->links() }}
            </div>
        </div>
    </div>


    <div class="modal fade" id="searchProduct" tabindex="-1" role="dialog" aria-labelledby="searchProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="searchProductLabel">Add Stock</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-flush">
                      <thead>
                        <tr>
                          <th>no</th>
                          <th>kode produk</th>
                          <th>nama produk</th>
                          <th>stock</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($products as $index => $product)
                        <tr>
                        <th role="row">{{$i++}}</th>
                          <td>{{ $product->kodebrg }}</td>
                          <td>{{ $product->nama_product }}</td>
                          <td>@isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                            @else
                                <span class="text-danger">0</span>
                            @endisset
                          </td>
                          <td><button class="btn btn-primary btn-sm">pilih</button></td>
                        </tr>
                        @endforeach
                      </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
      $(document).ready(function(){
        $('#sp').click(function(){
          $('#addStock').modal('toggle');
          $('#searchProduct').modal('toggle');
        });
      });
    </script>
@endpush