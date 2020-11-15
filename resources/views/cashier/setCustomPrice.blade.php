@extends('dashboard-layout.master')

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
                            <td><a href="#setHarga" data-cp="{{$item->id}}" data-toggle="modal" ><span class="badge badge-lg badge-primary">set harga</span></a></td>
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
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
@endsection