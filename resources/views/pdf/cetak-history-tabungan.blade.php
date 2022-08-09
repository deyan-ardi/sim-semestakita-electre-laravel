@extends('pdf.layout.app')

@section('title', $title)

@section('header')
    <header style="border-bottom:1px solid #DDD">
        <div class="float-left w-50 text-left">
            <img src="{{ config('mitra.icon_text') }}" alt="{{ config('mitra.name') }}" width="180px">
        </div>
        <div class="float-right w-50 text-right">
            <h4>HISTORY-TABUNGAN-{{ $user->no_member }}</h4>
        </div>
    </header>
@endsection
@section('footer')
    <footer>
        <p><i>Dicetak Oleh {{ config('mitra.name') }}</i></p>
    </footer>
@endsection

@section('content')
    <table class="w-100">
        <tr>
            <td class="w-50 text-left">
                <table class="w-100">
                    <tr>
                        <td width="120px">
                            <b>Nama</b>
                        </td>
                        <td>: {{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Kode Member</b>
                        </td>
                        <td>: {{ $user->no_member }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>No WhatsApp</b>
                        </td>
                        <td>: {{ empty($user->no_telp) ? '~' : $user->no_telp }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Email</b>
                        </td>
                        <td>: {{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Jenis Transaksi</b>
                        </td>
                        <td>: History Tabungan</td>
                    </tr>
                </table>
            </td>
            <td class="w-50 text-right">
                <table class="w-100">
                    <tr>
                        <td></td>
                        <td width="120px" class="text-right">
                            <div class="badge badge-pill text-uppercase w-100 text-center text-light badge-success"
                                style="font-size:18px">
                                Sukses
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Tanggal Cetak</b>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::now()->format('d F Y H:i') . ' WITA' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>Pegawai</b>
                        </td>
                        <td>
                            {{ Auth::user()->name }}
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
    <div class="mt-3">
        <h6 class="mb-3">Penyetoran/Kredit</h6>
        <table class="table table-sm table-striped" style="width: 100%;">
            <thead>
                <tr class="text-white">
                    <td style="background-color:#7460ee; width:25px; vertical-align:middle;text-align: center;">
                        No.
                    </td>
                    <td style="background-color:#7460ee; vertical-align:middle;">Tanggal</td>
                    <td style="background-color:#7460ee; vertical-align:middle;">Kode Transaksi</td>
                    <td style="background-color:#7460ee;vertical-align: middle;" width="160px" class="text-right">Sub
                        Total
                        (Dalam IDR)</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($rekapan_sampah as $i => $r)
                    @php
                        $total = $total + $r->total_beli;
                    @endphp
                    <tr>
                        <td style="width:25px; vertical-align:middle;text-align: center;">{{ $i + 1 }}</td>
                        <td> {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }} WITA</td>
                        <td>{{ $r->kode_transaksi }}</td>
                        <td class="text-right">Rp. {{ number_format($r->total_beli, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="3">
                        Jumlah Penyetoran
                    </td>
                    <td class="text-right">
                        {{ $rekapan_sampah->count() }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" colspan="3">
                        Total Penyetoran
                    </td>
                    <td class="text-right text-success">
                        Rp. {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>


        <h6 class="mb-3 mt-4">Penarikan/Debet</h6>
        <table class="table table-sm table-striped" style="width: 100%;">
            <thead>
                <tr class="text-white">
                    <td style="background-color:#7460ee; width:25px; vertical-align:middle;text-align: center;">
                        No.
                    </td>
                    <td style="background-color:#7460ee; vertical-align:middle;">Tanggal</td>
                    <td style="background-color:#7460ee; vertical-align:middle;">Kode Transaksi</td>
                    <td style="background-color:#7460ee;vertical-align: middle;" width="160px" class="text-right">Sub
                        Total
                        (Dalam IDR)</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_penarikan = 0;
                @endphp
                @foreach ($rekapan_penarikan as $i => $r)
                    @php
                        $total_penarikan = $total + $r->total_beli;
                    @endphp
                    <tr>
                        <td style="width:25px; vertical-align:middle;text-align: center;">{{ $i + 1 }}</td>
                        <td> {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }} WITA</td>
                        <td>{{ $r->no_penarikan }}</td>
                        <td class="text-right">Rp. {{ number_format($r->total_penarikan, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="3">
                        Jumlah Penarikan
                    </td>
                    <td class="text-right">
                        {{ $rekapan_penarikan->count() }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" colspan="3">
                        Total Penarikan
                    </td>
                    <td class="text-right text-success">
                        Rp. {{ number_format($total_penarikan, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            {{-- <img src='{{ storage_path("app/public/qrcode/$rekapan->no_tagihan.png") }}' alt="QRCODE" width="100px"> --}}
        </div>
    </div>
@endsection
