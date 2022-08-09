<!-- Sidebar wrapper start -->
<nav class="sidebar-wrapper">

    <!-- Default sidebar wrapper start -->
    <div class="default-sidebar-wrapper">

        <!-- Sidebar brand starts -->
        <div class="default-sidebar-brand">
            <a href="{{ route('admin') }}" class="logo">
                <img src="{{ config('mitra.icon_text') }}" alt="{{ config('mitra.name') }}">
            </a>
        </div>
        <!-- Sidebar brand starts -->

        <!-- Sidebar menu starts -->
        <div class="defaultSidebarMenuScroll">
            <div class="default-sidebar-menu">
                <ul>
                    <li class="default-sidebar {{ Route::is('admin') || Route::is('admin.*') ? 'active' : '' }}">
                        <a href="{{ route('admin') }}"
                            class="{{ Route::is('admin') || Route::is('admin.*') ? 'current-page' : '' }}">
                            <i class="icon-home2"></i>
                            <span class="menu-text">Dashboard</span>
                        </a>
                    </li>
                    @if (Auth::user()->role != 6)
                        <li
                            class="default-sidebar-dropdown {{ Route::is('penyetoran') ||Route::is('penyetoran.*') ||Route::is('tabungan') ||Route::is('tabungan.*') ||Route::is('iuran.kasir') ||Route::is('iuran.kasir.*') ||Route::is('harian') ||Route::is('harian.*')? 'active': '' }}">
                            <a href="javascript:void(0)">
                                <i class="icon-store_mall_directory"></i>
                                <span class="menu-text">Menu Kasir</span>
                            </a>
                            <div class="default-sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="{{ route('penyetoran') }}"
                                            class="{{ Route::is('penyetoran') || Route::is('penyetoran.*') ? 'current-page' : '' }}">
                                            Nabung
                                            Sampah Nasabah</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('tabungan') }}"
                                            class="{{ Route::is('tabungan') || Route::is('tabungan.*') ? 'current-page' : '' }}">
                                            Tarik
                                            Tabungan Nasabah</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('iuran.kasir') }}"
                                            class="{{ Route::is('iuran.kasir') || Route::is('iuran.kasir.*') ? 'current-page' : '' }}">
                                            Pembayaran
                                            Iuran</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('harian') }}"
                                            class="{{ Route::is('harian') || Route::is('harian.*') ? 'current-page' : '' }}">
                                            Pendataan Sampah Harian</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    <li
                        class="default-sidebar-dropdown {{ Route::is('penjemputan') ||Route::is('penjemputan.*') ||Route::is('pengangkutan.terjadwal') ||Route::is('pengangkutan.terjadwal.*') ||Route::is('pengangkutan-penilaian') ||Route::is('pengangkutan-penilaian.*')? 'active': '' }}">
                        <a href="javascript:void(0)">
                            <i class="icon-truck"></i>
                            <span class="menu-text">Pengangkutan Sampah</span>
                        </a>
                        <div class="default-sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('penjemputan') }}"
                                        class="{{ Route::is('penjemputan') || Route::is('penjemputan.*') ? 'current-page' : '' }}">
                                        Permintaan Pengangkutan</a>
                                </li>
                                {{-- <li>
                                    <a href="{{ route('pengangkutan.terjadwal') }}"
                                        class="{{ Route::is('pengangkutan.terjadwal') || Route::is('pengangkutan.terjadwal.*') ? 'current-page' : '' }}">
                                        Pengangkutan Terjadwal</a>
                                </li> --}}
                                <li>
                                    <a href="{{ route('pengangkutan-penilaian') }}"
                                        class="{{ Route::is('pengangkutan-penilaian') || Route::is('pengangkutan-penilaian.*') ? 'current-page' : '' }}">Pengangkutan
                                        & Penilaian</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="default-sidebar {{ Route::is('tagihan') || Route::is('tagihan.*') ? 'active' : '' }}">
                        <a href="{{ route('tagihan') }}"
                            class="{{ Route::is('tagihan') || Route::is('tagihan.*') ? 'current-page' : '' }}">
                            <i class="icon-dollar-sign"></i>
                            <span class="menu-text">Daftar Tagihan Iuran</span>

                        </a>
                    </li>
                    <li
                        class="default-sidebar-dropdown  {{ Route::is('rekapan-sampah') ||Route::is('rekapan-sampah.*') ||Route::is('rekapan-tabungan') ||Route::is('rekapan-tabungan.*') ||Route::is('rekapan-iuran') ||Route::is('rekapan-iuran.*') ||Route::is('rekapan-harian') ||Route::is('rekapan-harian.*') ||Route::is('list-tabungan') ||Route::is('list-tabungan.*') ||Route::is('rekapan-penilaian') ||Route::is('rekapan-penilaian.*')? 'active': '' }}">
                        <a href="javascript:void(0)">
                            <i class="icon-printer"></i>
                            <span class="menu-text">Menu Laporan</span>
                        </a>
                        <div class="default-sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('rekapan-sampah') }}"
                                        class="{{ Route::is('rekapan-sampah') || Route::is('rekapan-sampah.*') ? 'current-page' : '' }}">
                                        Nabung Sampah
                                        Nasabah</a>
                                </li>
                                <li>
                                    <a href="{{ route('rekapan-tabungan') }}"
                                        class="{{ Route::is('rekapan-tabungan') || Route::is('rekapan-tabungan.*') ? 'current-page' : '' }}">
                                        Tarik
                                        Tabungan Nasabah</a>
                                </li>
                                <li>
                                    <a href="{{ route('list-tabungan') }}"
                                        class="{{ Route::is('list-tabungan') || Route::is('list-tabungan.*') ? 'current-page' : '' }}">
                                        Tabungan
                                        Nasabah</a>
                                </li>
                                <li>
                                    <a href="{{ route('rekapan-iuran') }}"
                                        class="{{ Route::is('rekapan-iuran') || Route::is('rekapan-iuran.*') ? 'current-page' : '' }}">
                                        Pembayaran
                                        Iuran</a>
                                </li>
                                <li>
                                    <a href="{{ route('rekapan-harian') }}"
                                        class="{{ Route::is('rekapan-harian') || Route::is('rekapan-harian.*') ? 'current-page' : '' }}">
                                        Sampah
                                        Harian</a>
                                </li>


                                <li>
                                    <a href="{{ route('rekapan-penilaian') }}"
                                        class="{{ Route::is('rekapan-penilaian') || Route::is('rekapan-penilaian.*') ? 'current-page' : '' }}">
                                        Pengangkutan & Penilaian </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li
                        class="default-sidebar-dropdown {{ Route::is('statistik.keseluruhan') ||Route::is('statistik.keseluruhan.*') ||Route::is('statistik.harian') ||Route::is('statistik.harian.*')? 'active': '' }}">
                        <a href="javascript:void(0)">
                            <i class="icon-pie-chart1"></i>
                            <span class="menu-text">Statistik Sampah</span>
                        </a>
                        <div class="default-sidebar-submenu">
                            <ul>
                                <li>
                                    <a href="{{ route('statistik.keseluruhan') }}"
                                        class="{{ Route::is('statistik.keseluruhan') || Route::is('statistik.keseluruhan.*') ? 'current-page' : '' }}">
                                        Sampah Keseluruhan</a>
                                </li>
                                <li>
                                    <a href="{{ route('statistik.harian') }}"
                                        class="{{ Route::is('statistik.harian') || Route::is('statistik.harian.*') ? 'current-page' : '' }}">
                                        Sampah Harian</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    @if (Auth::user()->role == 1 || Auth::user()->role == 2)
                        <li
                            class="default-sidebar-dropdown {{ Route::is('iuran.master') ||Route::is('iuran.master.*') ||Route::is('kategori') ||Route::is('kategori.*') ||Route::is('pelanggan') ||Route::is('pelanggan.*') ||Route::is('nasabah') ||Route::is('nasabah.*') ||Route::is('kriteria-penilaian') ||Route::is('kriteria-penilaian.*') ||Route::is('konfigurasi') ||Route::is('konfigurasi.*') ||Route::is('user') ||Route::is('user.*') ||Route::is('sistem.info') ||Route::is('sistem.info.*')? 'active': '' }}">
                            <a href="javascript:void(0)">
                                <i class="icon-database"></i>
                                <span class="menu-text">Menu Master Data</span>
                            </a>
                            <div class="default-sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="{{ route('iuran.master') }}"
                                            class="{{ Route::is('iuran.master') || Route::is('iuran.master.*') ? 'current-page' : '' }}">Iuran
                                            Rutin</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('kategori') }}"
                                            class="{{ Route::is('kategori') || Route::is('kategori.*') ? 'current-page' : '' }}">Kategori
                                            Sampah</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pelanggan') }}"
                                            class="{{ Route::is('pelanggan') || Route::is('pelanggan.*') ? 'current-page' : '' }}">Data
                                            Pelanggan</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('nasabah') }}"
                                            class="{{ Route::is('nasabah') || Route::is('nasabah.*') ? 'current-page' : '' }}">Data
                                            Nasabah</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('kriteria-penilaian') }}"
                                            class="{{ Route::is('kriteria-penilaian') || Route::is('kriteria-penilaian.*') ? 'current-page' : '' }}">Data
                                            Kriteria Penilaian</a>
                                    </li>
                                    @if (Auth::user()->role == 1)
                                        <li>
                                            <a href="{{ route('user') }}"
                                                class="{{ Route::is('user') || Route::is('user.*') ? 'current-page' : '' }}">Data
                                                Pengguna Sistem</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('sistem.info') }}"
                                                class="{{ Route::is('sistem.info') || Route::is('sistem.info.*') ? 'current-page' : '' }}">Informasi
                                                Sistem</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('konfigurasi') }}"
                                            class="{{ Route::is('konfigurasi') || Route::is('konfigurasi.*') ? 'current-page' : '' }}">Konfigurasi
                                            Dasar</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    @if (Auth::user()->role != 6)
                        <li
                            class="default-sidebar  {{ Route::is('artikel') || Route::is('artikel.*') ? 'active' : '' }}">
                            <a href="{{ route('artikel') }}"
                                class="{{ Route::is('artikel') || Route::is('artikel.*') ? 'current-page' : '' }}">
                                <i class="icon-globe"></i>
                                <span class="menu-text">Artikel & Produk</span>
                            </a>
                        </li>
                        <li
                            class="default-sidebar-dropdown {{ Route::is('notifikasi') || Route::is('notifikasi.*') || Route::is('pengaduan') || Route::is('pengaduan.*')? 'active': '' }}">
                            <a href="javascript:void(0)">
                                <i class="icon-bell"></i>
                                <span class="menu-text">Notifikasi & Pengaduan</span>
                            </a>
                            <div class="default-sidebar-submenu">
                                <ul>
                                    <li>
                                        <a href="{{ route('notifikasi') }}"
                                            class="{{ Route::is('notifikasi') || Route::is('notifikasi.*') ? 'current-page' : '' }}">Manajemen
                                            Notifikasi</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('pengaduan') }}"
                                            class="{{ Route::is('pengaduan') || Route::is('pengaduan.*') ? 'current-page' : '' }}">Kritik,
                                            Pengaduan, & Saran</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- Sidebar menu ends -->

    </div>
    <!-- Default sidebar wrapper end -->

</nav>
<!-- Sidebar wrapper end -->
