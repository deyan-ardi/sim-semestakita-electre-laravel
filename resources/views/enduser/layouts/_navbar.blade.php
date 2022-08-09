<div class="header">

    <div class="header-left">
        <a href="{{ route('enduser.dashboard') }}" class="logo">
            <img src="{{ config('mitra.icon_text') }}" alt="Logo">
        </a>
        <a href="{{ route('enduser.dashboard') }}" class="logo logo-small">
            <img src="{{ config('mitra.icon') }}" alt="Logo" width="30" height="30">
        </a>
    </div>


    <a href="javascript:void(0);" id="toggle_btn">
        <i class="fas fa-bars"></i>
    </a>

    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>


    <ul class="nav user-menu">
        @php
            $belum = 0;
            foreach ($notifikasi as $n) {
                if ($n->status == 'belum') {
                    $belum++;
                }
            }
        @endphp
        <li class="nav-item dropdown">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <i data-feather="bell"></i> <span class="badge badge-pill">{{ $belum }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">( {{ $belum }} ) Belum Dibaca</span>
                    <a href="{{ route('enduser.notifikasi.index') }}" class="clear-noti text-primary"> Lihat Info
                        Pengelola</a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        @if ($notifikasi->count() <= 0)
                            <li class="notification-message">
                                <a href="#!">
                                    <div class="media">
                                        <div class="media-body">
                                            <p class="noti-details"><span class="noti-title">Tidak Ada
                                                    Notifikasi</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @else
                            @foreach ($notifikasi as $n)
                                <li class="notification-message">
                                    <a href="{{ $n->key == 'notif' ? route('enduser.notifikasi.index') : route('enduser.notifikasi.sistem', [$n->id]) }}"
                                        class="{{ $n->status == 'belum' ? 'bg-light' : '' }}">
                                        <div class="media">
                                            <div class="media-body">
                                                <p class="noti-details"><span
                                                        class="noti-title">{{ $n->judul }}</span>
                                                    {{ substr(ucWords(preg_replace('/<[^>]*>/', '', $n->konten)), 0, 50) }}...
                                                </p>
                                                <p class="noti-time"><span
                                                        class="notification-time">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </li>


        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <span class="user-img">
                    <img id="avatarImg" src="{{ $file }}" alt="Profile Image">
                </span>
                @php
                    $pecah = explode(' ', Auth::user()->name);
                @endphp
                <span>Halo {{ $pecah[0] }}..</span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('enduser.profil.index') }}"><i data-feather="user"
                        class="mr-1"></i>
                    Data Profil</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="$('#logout-form').submit()"><i
                        data-feather="log-out" class="mr-1"></i>
                    Keluar</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                </form>
            </div>
        </li>

    </ul>

</div>
