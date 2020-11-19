@extends('dashboard-layout.master')

@section('content')
    <div class="container-fluid mt-5">
    <a href="{{ url()->previous() }}" class="btn btn-dark mb-3">back</a>
        <div class="card shadow-none">
            <div class="card-header d-flex align-items-center">
                <div class="col">
                    Aktivitas Stok
                </div>
                <div class="col text-right">
                    <button type="button" class="btn btn-default btn-icon-only" data-container="body" data-html="true" data-toggle="popover" 
                    data-placement="bottom" data-content="<div>
                                    <i class='fas fa-arrow-up text-success mr-3'></i> : Masuk (in)
                                    </div>
                                    <div><i class='fas fa-arrow-down text-danger mr-3'></i> : Keluar (out)</div>
                                    <div><i class='fas fa-clock text-warning mr-3'></i> : Pending (out)</div>">
                      <i class="fa fa-question-circle"></i>
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-flush table-hover">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th rowspan="2">#</th>
                            <th colspan="2">keterangan</th>
                            <th rowspan="2">status</th>
                            <th rowspan="2">waktu</th>
                        </tr>
                        <tr>
                            <th>kode barang</th>
                            <th>barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockActivity as $index => $sa)
                        <tr>
                            <td>{{ $stockActivity->firstitem() + $index }}</td>
                            <td>({{ $sa->product->kodebrg }})</td>
                            <td>{{ $sa->product->nama_product }}</td>
                            <td class="text-center">
                                @if ($sa->type_activity == "in" || $sa->type_activity == "add")
                                    <i class="fas fa-arrow-up text-success mr-3"></i>
                                @elseif($sa->type_activity == "out")
                                    <i class="fas fa-arrow-down text-danger mr-3"></i>
                                @elseif($sa->type_activity == "pending")
                                    <i class="fas fa-clock text-warning mr-3"></i>
                                @elseif($sa->type_activity == "destroy")
                                    <i class="fas fa-trash text-warning mr-3"></i>
                                @endif
                                 {{ $sa->stock }} {{ $sa->product->unit->unit }}</td>
                            <td class="text-center">
                                <span data-toggle="tooltip" data-placement="top" title="{{ $sa->created_at }}">{{Carbon\Carbon::parse($sa->created_at)->diffForHumans()}}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-center">
                {{$stockActivity->links()}}
            </div>
        </div>
    </div>
@endsection