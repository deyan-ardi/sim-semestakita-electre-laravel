<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title"><span>Menu</span></li>
                <li>
                    <a class="{{ Route::is('enduser.bantuan.index') ? 'active' : '' }}"
                        href="{{ route('enduser.bantuan.index') }}"><i class="fas fa-question"></i>
                        <span>Bantuan</span></a>
                </li>
                <li>
                    <a class="{{ Route::is('enduser.notifikasi.index') ? 'active' : '' }}"
                        href="{{ route('enduser.notifikasi.index') }}"><i class="fas fa-comments"></i>
                        <span>Pengumuman</span></a>
                </li>
                <li>
                    <a class="{{ Route::is('enduser.hubungi.index') ? 'active' : '' }}"
                        href="{{ route('enduser.hubungi.index') }}"><i class="fas fa-phone"></i> <span>Hubungi
                            Pengelola</span></a>
                </li>
                <li>
                    <a class="{{ Route::is('enduser.tentang.index') ? 'active' : '' }}"
                        href="{{ route('enduser.tentang.index') }}"><i class="fas fa-info"></i> <span>Tentang
                            Aplikasi</span></a>
                </li>
                <li class="menu-title"><span>Lainnya</span></li>
                <li>
                    <a href="javascript:void(0)" onclick="$('#logout-form').submit()"><i
                            class="fas fa-sign-out-alt"></i><span>Keluar</span></a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
