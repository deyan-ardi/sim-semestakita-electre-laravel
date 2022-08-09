@extends('pdf.layout.app')

@section('title', 'Cetak Kode QR')

@section('content')

    @foreach ($user as $item)
        <div style="border: 3px solid #0f0f0f; padding:3px">
            <center>{{ config('mitra.name') }}</center>
            <center> <img src="data:image/png;base64, {!! base64_encode(QrCode::size(212)->generate($item->id)) !!} "></center>
            <center>{{ $item->no_member }}</center>
        </div>
        <br><br>
    @endforeach

@endsection
