@php
    $constCompany = DB::table('about_us')->first();
    if($constCompany == null) {
                $constNamaCompany = "App POS";
            }else{
                $constNamaCompany = $constCompany->name;
            }
@endphp
@section('footer')

<footer class="footer pt-0 pb-0" style="margin-top: 300px">
    <div class="d-flex align-items-center justify-content-lg-between bg-dark py-5 ">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3 d-flex flex-column align-items-start">
                    <div class="item d-flex flex-column mb-3">
                        <div class="">
                            <i class="fa fa-phone-alt"></i>&nbsp;phone
                        </div>
                        <span class="text-muted">{{$constCompany->phone}}</span>
                    </div>
                    <div class="item d-flex flex-column">
                        <div class="">
                            <i class="fa fa-info-circle"></i>&nbsp;about us
                        </div>
                        <span class="text-muted">{{$constCompany->about}}</span>
                    </div>
                </div>
                <div class="col-lg-6 justify-content-center text-left">
                    <div class="item d-flex flex-column">
                        <div class="">
                            <i class="fa fa-map-marker-alt"></i>&nbsp;address
                        </div>
                        <span class="text-muted">{{$constCompany->address}}</span>
                    </div>
                </div>
            </div>
            <div class="copyright text-center mt-3 pt-4 text-muted align-items-center">
                &copy; 2020 <a href="{{ url('/') }}" class="font-weight-bold ml-1" target="_blank">{{$constNamaCompany}}</a>
            </div>
        </div>
    </div>
</footer>
@endsection