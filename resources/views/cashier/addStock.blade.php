@extends('dashboard-layout.master')

@section('content')
    <div class="container-fluid mt-5">
        <div class="search">
            <form action="">
                <input type="text" class="form-control form-control-muted col-7" placeholder="search product">
            </form>
            <div class="text-right mb-3">
                <a href="#" class="badge badge-danger badge-lg">stock habis</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                    <h3 class="mb-0">s</h3>
                    <div class="">
                        <a data-toggle="modal" href="#addStock" role="button" class="btn btn-sm btn-success btn-round btn-icon" data-toggle="tooltip" data-original-title="tambah stock product">
                            <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                            <span class="btn-inner--text">Tambah</span>
                        </a>
                        <a href="#" role="button" class="btn btn-sm btn-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="Filter product">
                            <span class="btn-inner--icon"><i class="fas fa-sliders-h"></i></span>
                            <span class="btn-inner--text">Filter</span>
                        </a>
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
                        @foreach ($products as $index => $product)
                        <tr>
                            <td>x</td>
                            <th scope="row">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar rounded-circle mr-3">
                                      <img alt="Image placeholder" src="{{ asset($product->img) }}" alt="gambar {{ $product->nama_product }}">
                                    </a>
                                    <div class="media-body">
                                      <span class="name mb-0 text-sm">{{$product->nama_product}}</span>
                                      <span class="badge badge-pill badge-info">d</span>
                                    </div>
                                </div>
                            </th>
                            <td>@isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                                @else
                                    <span class="text-danger">stok habis</span>
                                @endisset</td>
                            <td class="table-actions">
                                <a href="#!" class="text-gray" data-toggle="tooltip" data-original-title="tambah stock">
                                    <i class="fas fa-plus-square"></i>
                                  </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="row">
            @foreach ($products as $product)
                <a href="#" class="col-sm-2 col-md-3 col-xs-2  p-0">
                    <div class="card m-3 shadow-sm p-0 m-0" style="background: rgba(155, 89, 182, 0.3)">
                        <img class="card-img-top" style="align-self: center" src="{{ asset($product->img) }}" alt="gambar {{ $product->nama_product }}">
                        <div class="card-body">
                            <h3 class="text-dark">
                                {{ $product->nama_product }}
                            </h3>
                            <h2 class="priceProduct text-gray">{{ $product->price }}</h2>
                            <h5 class="text-muted">Tersedia :
                            @isset($product->stocks) {{ $product->stocks->stock }} {{ $product->unit->unit }}
                            @else
                                <span class="text-danger">stok habis</span>
                            @endisset
                            </h5>
                        </div>
                    </div>
                </a>    
            @endforeach
        </div> --}}
    </div>

    <div class="modal fade" id="addStock" tabindex="-1" role="dialog" aria-labelledby="addStockLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addStockLabel">Add Stock</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form action="" method="POST">
                <div class="form-group">
                    <label for="formControlRange">Example Range input</label>
                    <input type="range" class="form-control-range" id="formControlRange" min="1" max="3" value="2">
                  </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
@endsection