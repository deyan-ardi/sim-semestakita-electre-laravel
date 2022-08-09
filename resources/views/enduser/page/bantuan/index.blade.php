@extends('enduser.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Bantuan')
@section('meta-description', 'Data Bantuan')
@section('meta-keyword', 'Bantuan')
{{-- End Meta --}}
@section('title', 'Bantuan')
@section('content')
<div class="content container-fluid faq-section">
    <div class="row">
        <div class="col-12 m-b-100">
            <div class="section-header">
                <h3 class="section-title">Bantuan</h3>
                <div class="line"></div>
            </div>

            <!--Accordion wrapper-->
            <div class="accordion md-accordion" id="faq-accordion" role="tablist" aria-multiselectable="true">

                <!-- Accordion card -->
                <div class="card">

                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-1">
                        <a data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-1" aria-expanded="true"
                            aria-controls="collapse-1">
                            <h5 class="mb-0">
                                Bagaimana caranya saya melakukan penarikan tabungan? <i
                                    class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>

                    <!-- Card body -->
                    <div id="collapse-1" class="collapse show" role="tabpanel" aria-labelledby="heading-1"
                        data-parent="#faq-accordion">
                        <div class="card-body">
                            Khusus nasabah, untuk melakukan penarikan tabungan silahkan mengunjungi pengelola TPST untuk melakukan
                            penarikan tabungan, pengelola akan memasukkan data anda lalu anda dapat menarik tabungan
                            sampah anda. Ingat untuk meminta bukti transaksi penarikan tabungan anda. History transaksi
                            juga dapat dilihat melalui menu rekap transaksi
                        </div>
                    </div>

                </div>
                <!-- Accordion card -->

                <!-- Accordion card -->
                <div class="card">

                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-2">
                        <a class="collapsed" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-2"
                            aria-expanded="false" aria-controls="collapse-2">
                            <h5 class="mb-0">
                                Apakah terdapat denda jika saya tidak membayar tagihan bulanan? <i
                                    class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>

                    <!-- Card body -->
                    <div id="collapse-2" class="collapse" role="tabpanel" aria-labelledby="heading-2"
                        data-parent="#faq-accordion">
                        <div class="card-body">
                            Tidak ada denda ketika anda terlambat membayar tagihan iuran bulanan, namun jika anda terus
                            menerus tidak membayar iuran maka kami tidak akan melakukan penjemputan harian pada sampah
                            Anda sampai anda melunasi tagihan iuran bulanan yang ada.
                        </div>
                    </div>

                </div>
                <!-- Accordion card -->

                <!-- Accordion card -->
                <div class="card">

                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-3">
                        <a class="collapsed" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-3"
                            aria-expanded="false" aria-controls="collapse-3">
                            <h5 class="mb-0">
                                Apakah sampah akan diangkut setiap hari? <i class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>

                    <!-- Card body -->
                    <div id="collapse-3" class="collapse" role="tabpanel" aria-labelledby="heading-3"
                        data-parent="#faq-accordion">
                        <div class="card-body">
                            Kami telah mengatur penjadwalan penjemputan sampah harian bagi pelanggan kami, sampah anda
                            akan diambil setiap 2 hari sekali.
                        </div>
                    </div>

                </div>
                <!-- Accordion card -->

                <!-- Accordion card -->
                <div class="card">

                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-4">
                        <a class="collapsed" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-4"
                            aria-expanded="false" aria-controls="collapse-4">
                            <h5 class="mb-0">
                                Bagaimana cara bergabung menjadi nasabah? <i class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>

                    <!-- Card body -->
                    <div id="collapse-4" class="collapse" role="tabpanel" aria-labelledby="heading-4"
                        data-parent="#faq-accordion">
                        <div class="card-body">
                            Untuk bergabung menjadi nasabah dan mulai menabung, silahkan hubungi pengelola TPST untuk
                            mengubah status pelanggan anda menjadi nasabah. Anda mungkin diminta untuk mengisi beberapa
                            tambahan untuk hal ini. Khusus nasabah, segala informasi yang berkaitan dengan transaksi
                            tabungan, penarikan, penyetoran dapat melalui sistem ini
                        </div>
                    </div>

                </div>
                <!-- Accordion card -->
                <!-- Accordion card -->
                <div class="card">

                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-5">
                        <a class="collapsed" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-5"
                            aria-expanded="false" aria-controls="collapse-5">
                            <h5 class="mb-0">
                                Bagaimana cara menabung sampah? <i class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>

                    <!-- Card body -->
                    <div id="collapse-5" class="collapse" role="tabpanel" aria-labelledby="heading-5"
                        data-parent="#faq-accordion">
                        <div class="card-body">
                            Khusus nasabah, anda dapat melakukan penyetoran sampah. Silahkan bawa sampah anda sesuai dengan jenis sampah yang dijinkan ke pengelola TPST, tim pengelola akan menginputkan total sampah yang dibawa kedalam sistem, harga jenis sampah dapat berubah di tiap harinya, total transaksi sampah yang anda bawa akan otomatis masuk kedalam saldo tabungan anda. Jangan lupa meminta bukti transaksi setelah melakukan penyetoran sampah.
                        </div>
                    </div>

                </div>
                <!-- Accordion card -->

                <!-- Accordion card -->
                <div class="card">
                
                    <!-- Card header -->
                    <div class="card-header" role="tab" id="heading-6">
                        <a class="collapsed" data-toggle="collapse" data-parent="#faq-accordion" href="#collapse-6"
                            aria-expanded="false" aria-controls="collapse-6">
                            <h5 class="mb-0">
                                Bagaimana cara berhenti menjadi pelanggan/nasabah? <i class="fas fa-angle-down rotate-icon"></i>
                            </h5>
                        </a>
                    </div>
                
                    <!-- Card body -->
                    <div id="collapse-6" class="collapse" role="tabpanel" aria-labelledby="heading-6" data-parent="#faq-accordion">
                        <div class="card-body">
                            Silahkan konsultasikan kepada pihak pengelola TPST terkait rencana anda untuk berhenti menjadi nasabah/pelanggan. Tim pengelola akan menonaktifkan status iuran anda jika anda berhenti menjadi pelanggan. Namun jangan khawatir, anda dapat bergabung kembali tanpa kehilangan data sebelumnya, termasuk sisa saldo (selama data anda tidak terhapus).
                        </div>
                    </div>
                
                </div>
                <!-- Accordion card -->
            </div>
            <!-- Accordion wrapper -->
        </div>
    </div>
</div>
@endsection