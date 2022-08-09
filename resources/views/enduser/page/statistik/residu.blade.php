@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Statistik Sampah Residu')
@section('meta-description', 'Data Statistik Sampah Residu')
@section('meta-keyword', 'Statistik Sampah Residu')
{{-- End Meta --}}
@section('title', 'Statistik Sampah Residu')
@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-12 m-b-100">
                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="section-title">Statistik Sampah Residu</h3>
                                <div class="line"></div>
                            </div>
                        </div>
                    </div>
                    @php
                        $jml_residu = 0;
                        foreach ($all_sampah_residu as $sampah) {
                            $jml_residu = $jml_residu + $sampah->total_sampah;
                        }
                        
                    @endphp
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="graph-day-selection p-3 text-center" role="group">
                                    <a href="#"><button type="button"
                                            class="btn active">{{ \Carbon\Carbon::now()->format('d F Y,H:i') }}
                                            WITA</button></a>
                                </div>
                                <div class="card-body p-3 pr-5 overflow-auto">
                                    <div id="earningsGraphResidu" class="chart-height-md"></div>

                                    <ul class="stats-list-container">
                                        <li class="stats-list-item primary">
                                            <div class="stats-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path
                                                        d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                    <path fill-rule="evenodd"
                                                        d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                </svg>
                                            </div>
                                            <div class="stats-info text-center">
                                                <h6 class="stats-title">Total Sampah Residu</h6>
                                                <p class="stats-amount">{{ $jml_residu }} Kg</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom-script')
    <script>
        // Residu
        var residu_name = {!! $residu_name !!};
        var residu_jml = [{{ $residu_jml }}];
    </script>
    <script src="{{ asset('assets/enduser/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/enduser/plugins/apexchart/custom/statistik/earningsGraphResidu.js') }}"></script>
@endsection
