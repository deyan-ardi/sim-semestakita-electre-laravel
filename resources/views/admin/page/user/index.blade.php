@extends('admin.layouts.app')
{{-- Meta --}}
@section('meta-name', 'Master Data, User Sistem')
@section('meta-description', 'Data Master Data, User Sistem')
@section('meta-keyword', 'Master Data, User Sistem')
{{-- End Meta --}}
@section('title', 'Master - Data User Sistem')
@section('content')

    <!-- Content wrapper start -->
    <div class="content-wrapper">

        <!-- Row start -->
        <div class="row gutters">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                <h4 class="mb-3">Master - Data User Sistem</h4>
                <!-- Card start -->
                <div class="card">
                    <div class="card-header-lg">
                        <h4 class="d-flex align-items-center"><i class="icon-people icon-large me-3"></i>Jumlah User
                            ({{ $jumlahUser }}), Super Admin({{ $superAdmin }}), Pengelola({{ $pengelola }}),
                            Pegawai({{ $pegawai }}), Guest({{ $guest }})</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('user') }}" class="btn btn-primary rounded" type="button">
                                            <span class="icon-refresh refresh"></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

                <!-- Card start -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5 class="mt-3">Data User Sistem</h5>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-end">
                                    <div class="custom-btn-group">
                                        <a href="{{ route('user.tambah') }}" class="btn btn-primary rounded"
                                            type="button">+
                                            Tambah</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table id="highlightRowColumn" class="table custom-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>No. Member</th>
                                        <th>Nama</th>
                                        <th>Kontak</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <a href="{{ route('user.edit', [$user->id]) }}" data-toggle="tooltip"
                                                        data-placement="top" title="Ubah User" data-original-title="Edit">
                                                        <i class="icon-edit1 text-info"></i>
                                                    </a>
                                                    <form id="delete-{{ $user->id }}"
                                                        action="{{ route('user.delete', [$user->id]) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" data-formid="{{ $user->id }}"
                                                            data-nama="{{ $user->name }}" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus User "
                                                            class="btn btn-link text-decoration-none delete-button">
                                                            <i class="icon-trash text-danger"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td>{{ $user->no_member == null ? 'Belum Disetel' : $user->no_member }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->no_telp }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role == 1)
                                                    Super Admin
                                                @elseif ($user->role == 2)
                                                    Pengelola
                                                @elseif ($user->role == 3)
                                                    Pegawai
                                                @elseif ($user->role == 6)
                                                    Pihak Lain/Guest
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Card end -->

            </div>
        </div>
    </div>
    <!-- Row end -->

@endsection
