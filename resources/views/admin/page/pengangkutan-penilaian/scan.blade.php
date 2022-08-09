@extends('admin.layouts.kasir')
{{-- Meta --}}
@section('meta-name', 'Pengangkutan dan Penilaian Harian, Scan Barcode')
@section('meta-description', 'Data Pengangkutan dan Penilaian Harian, Scan Barcode')
@section('meta-keyword', 'Pengangkutan dan Penilaian Harian, Scan Barcode')
{{-- End Meta --}}
@section('title', 'Pengangkutan dan Penilaian Harian - Scan Barcode')
@section('footer')
    <script src="{{ asset('assets/admin/js/html5-qrcode.min.js') }}"></script>
    <script type="text/javascript">
        function onScanSuccess(qrCodeMessage) {
            $.ajax({
                url: "{{ route('pengangkutan-penilaian.scan.process') }}",
                method: 'POST',
                dataType: 'json',
                async: false,
                cache: false,
                data: {
                    id_user: qrCodeMessage,
                    _token: "{{ csrf_token() }}",
                },
                success: function(result) {
                    if (result.success) {
                        var redirect = "{{ route('pengangkutan-penilaian.scan.result', '') }}" + "/" + result
                            .info;
                        location.assign(redirect);
                    } else {
                        Swal.fire({
                            position: "top-end",
                            showConfirmButton: false,
                            text: result.info,
                            icon: "error",
                            timer: 3000
                        });
                    }
                },
                error: function(result) {
                    alert(result.responseText);
                }
            });
        }

        function onScanError(errorMessage) {
            document.getElementById('result').innerHTML = '<span class="result">' + errorMessage + '</span>';
        }
        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 30,
                qrbox: 250
            });
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>
@endsection
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12">
                <!-- Card start -->
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('pengangkutan-penilaian') }}" class="btn btn-danger mb-3"><i
                                class="icon-arrow-left"></i>Kembali</a>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4>Scan Kode QR Pengangkutan dan Penilaian Harian</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">

                            <!-- Row start -->
                            <div class="row justify-content-between">

                                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-5 col-12">
                                    <div id="reader"></div>
                                    <!-- Row start -->
                                    <div class="row gutters mt-2">
                                        <div class="col-12 mb-2">
                                            <small>This QR Scanner By <a href="https://github.com/mebjas/html5-qrcode"
                                                    class="text-primary">Code Scanner</a>, modified by <a
                                                    href="https://ganatech.my.id" class="text-primary">Ganatech
                                                    Solutions</a></small>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-7 col-12">
                                    <div class="row gutters">
                                        <div class="col-12">
                                            <div class="form-section-header light-bg">Data Hasil Scan Kode QR
                                            </div>

                                        </div>
                                        <div class="col-12">

                                            <div class="alert alert-danger" id="result">Data Tidak Ditemukan, Arahkan Kamera
                                                Pada Kode
                                                QR Yang Dimiliki Pelanggan dan Nasabah</div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- Row end -->
                            <div class="row justify-content-between mt-3">
                                <div class="row gutters">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="pt-3 pb-3">Kriteria Penilaian
                                                            Harian - Periode {{ \Carbon\Carbon::now()->format('F Y') }}
                                                        </th>
                                                        <th class="pt-3 pb-3">Opsi</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    @if ($get_all->count() > 0)

                                                        @foreach ($get_all as $item)
                                                            <tr>
                                                                <td>
                                                                    <h6 class="mt-2">{{ $item->urutan }}.
                                                                        {{ $item->nama_kriteria }}
                                                                    </h6>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                disabled>
                                                                            <label class="form-check-label">
                                                                                Iya, Melakukan
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio"
                                                                                disabled>
                                                                            <label class="form-check-label">
                                                                                Tidak Dilakukan
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="2">
                                                                <div class="alert alert-danger text-center">Belum Ada Data
                                                                    Kriteria
                                                                    Penilaian Untuk Periode Bulan Ini, Silahkan Atur
                                                                    Terlebih Dahulu Pada Menu Master Data
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <!-- Row end -->

                                <!-- Row start -->
                                <div class="row gutters">

                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-actions-footer">
                                            <form id="#" action="#" method="post">
                                                <div class="text-end">
                                                    <button class="btn btn-danger mt-2 confirm-cancel" disabled>Kosongkan
                                                        Form</button>
                                                    <button type="button" id="submit_btn" disabled
                                                        class="btn btn-primary ms-1 mt-2 confirm-save">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card end -->
            </div>
        </div>
        <!-- Row end -->
    </div>
@endsection
