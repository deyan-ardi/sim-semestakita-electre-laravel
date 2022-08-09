@extends('pdf.layout.app')

@section('title', $title)

@section('header')
    <header style="border-bottom:1px solid #DDD">
        <div class="float-left w-50 text-left">
            <img src="{{ config('mitra.icon_text') }}" alt="{{ config('mitra.name') }}" width="180px">
        </div>
        <div class="float-right w-50 text-right">
            <h4>{{ $rekapan->no_penarikan }}</h4>
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
                        <td>: {{ $rekapan->user->name }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Kode Member</b>
                        </td>
                        <td>: {{ $rekapan->user->no_member }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>No WhatsApp</b>
                        </td>
                        <td>: {{ empty($rekapan->user->no_telp) ? '~' : $rekapan->user->no_telp }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Email</b>
                        </td>
                        <td>: {{ $rekapan->user->email }}</td>
                    </tr>
                    <tr>
                        <td width="120px">
                            <b>Jenis Transaksi</b>
                        </td>
                        <td>: Penarikan Tabungan</td>
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
                            <b>Tanggal</b>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($rekapan->created_at)->format('d F Y H:i') . ' WITA' }}
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
        <table class="table table-sm table-striped" style="width: 100%;">
            <thead>
                <tr class="text-white">
                    <td style="background-color:#7460ee; width:25px; vertical-align:middle;text-align: center;">
                        No.
                    </td>
                    <td style="background-color:#7460ee; vertical-align:middle;">Item</td>
                    <td style="background-color:#7460ee;vertical-align: middle;" width="160px" class="text-right">Sub
                        Total Penarikan
                        (Dalam IDR)</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width:25px; vertical-align:middle;text-align: center;">1</td>
                    <td>Penarikan Tabungan Sampah</td>
                    <td class="text-right">Rp. {{ number_format($rekapan->total_penarikan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2">
                        Saldo Awal
                    </td>
                    <td class="text-right">
                        Rp. {{ number_format($rekapan->total_penarikan + $tabungan->saldo, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2">
                        Total Penarikan
                    </td>
                    <td class="text-right">
                        Rp. {{ number_format($rekapan->total_penarikan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td class="text-right" colspan="2">
                        Sisa Saldo
                    </td>
                    <td class="text-right text-success">
                        Rp. {{ number_format($tabungan->saldo, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            {{-- <img src='{{ storage_path("app/public/qrcode/$rekapan->no_tagihan.png") }}' alt="QRCODE" width="100px"> --}}
        </div>
    </div>
@endsection
