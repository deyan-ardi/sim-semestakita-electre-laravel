<div class="page-header">

    <!-- Row start -->
    <div class="row gutters">
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-6 col-9">

            <!-- Search container start -->
            <div class="search-container">

                <!-- Toggle sidebar start -->
                <div class="toggle-sidebar" id="toggle-sidebar">
                    <i class="icon-menu"></i>
                </div>
                <!-- Toggle sidebar end -->
            </div>
            <!-- Search container end -->

        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-3">

            <!-- Header actions start -->
            <ul class="header-actions">
                <li class="">
                    <a href=" {{ route('bantuan.info.sistem') }}" id="" aria-haspopup="true">
                        <i class="icon-info_outline text-success" style="font-size: 25px"></i>
                    </a>
                </li>
                @if (Auth::user()->role != 6)
                    @php
                        $belum = 0;
                    @endphp
                    @foreach ($system_notif as $all)
                        @if (Auth::user()->role == 1)
                            @php
                                $belum = $system_notif->count();
                            @endphp
                        @else
                            @if ($all->status == 'belum')
                                @php
                                    $belum++;
                                @endphp
                            @endif
                        @endif
                    @endforeach
                    <li class="dropdown-notifications">
                        <a href="javascript:void(0)" id="notifications" data-toggle="dropdown-notifications"
                            aria-haspopup="true">
                            <i class="icon-notifications_none text-success" style="font-size: 25px"></i>
                            <div class="border border-success rounded-circle text-center bg-success mb-3"
                                style="width: 14px; height:14px;">
                                <sup class="text-white">{{ $belum }}</sup>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end lrg" aria-labelledby="notifications">
                            <div class="dropdown-menu-header">
                                ({{ Auth::user()->role == 1 ? $system_notif->count() : $belum }})
                                {{ Auth::user()->role == 1 ? 'Notifikasi Sistem' : 'Pesan Belum Dibaca' }}
                            </div>
                            <div class="customScroll">
                                <ul class="header-notifications">
                                    @if ($system_notif->count() > 0)

                                        @foreach ($system_notif as $all)
                                            <li
                                                class="{{ $all->status == 'belum' && Auth::user()->role != 1 ? 'bg-light' : '' }}">
                                                <a href="{{ route('sistem.notif', [$all->id]) }}">
                                                    <div class="user-img online">
                                                        @if ($all->key == 'pengaduan')
                                                            <i class="icon-chat text-danger" style="font-size:2rem"></i>
                                                        @elseif($all->key == 'iuran')
                                                            <i class="icon-assignment_turned_in text-success"
                                                                style="font-size:2rem"></i>
                                                        @elseif($all->key == 'notif')
                                                            <i class="icon-notifications_none text-success"
                                                                style="font-size:2rem"></i>
                                                        @elseif($all->key == 'nabung')
                                                            <i class="icon-assignment_turned_in text-success"
                                                                style="font-size:2rem"></i>
                                                        @elseif($all->key == 'tarik')
                                                            <i class="icon-assignment_turned_in text-success"
                                                                style="font-size:2rem"></i>
                                                        @elseif($all->key == 'angkut')
                                                            <i class="icon-trash text-success"
                                                                style="font-size:2rem"></i>
                                                        @elseif($all->key == 'jemput')
                                                            <i class="icon-truck text-success"
                                                                style="font-size:2rem"></i>
                                                        @endif
                                                    </div>
                                                    <div class="details">
                                                        <div class="user-title">
                                                            {{ $all->key == 'pengaduan' ? 'PENGADUAN LAYANAN' : ($all->key == 'iuran' ? 'BAYAR TAGIHAN' : ($all->key == 'nabung' ? 'NABUNG SAMPAH' : ($all->key == 'tarik' ? 'PENARIKAN TABUNGAN' : ($all->key == 'angkut' ? 'ANGKUT SAMPAH HARIAN' : ($all->key == 'notif' ? 'NOTIFIKASI SISTEM' : 'PERMINTAAN JEMPUT SAMPAH'))))) }}
                                                        </div>
                                                        <div class="noti-details">{{ ucWords($all->judul) }}
                                                        </div>
                                                        <div class="noti-date">
                                                            {{ \Carbon\Carbon::parse($all->created_at)->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a href="javascript:void(0)">
                                                Belum Ada Notifikasi
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </li>
                @endif
                <li class="dropdown">
                    <a href="javascript:void(0)" id="userSettings" class="user-settings" data-toggle="dropdown"
                        aria-haspopup="true">
                        <span class="avatar">
                            <img src="{{ $file }}" alt="User Avatar">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end md" aria-labelledby="userSettings">
                        <div class="header-profile-actions">
                            <a href="{{ route('ganti.profil') }}"><i class="icon-user1"></i>Profil</a>
                            <a href="{{ route('ganti.keamanan') }}"><i class="icon-settings1"></i>Keamanan</a>
                            <a href="javascript:void(0)" onclick="$('#logout-form').submit()"><i
                                    class="icon-log-out1"></i>Keluar</a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
            <!-- Header actions end -->

        </div>
    </div>
    <!-- Row end -->

</div>
