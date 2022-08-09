<nav class="sidebar-wrapper">

    <!-- Sidebar content start -->
    <div class="sidebar-tabs">

        <!-- Tabs nav start -->
        <div class="nav" role="tablist">
            <a href="{{ route('admin') }}" class="logo">
                <img src="{{ config('mitra.icon') }}" alt="{{ config('mitra.name') }}">
            </a>
            <a class="nav-link {{ Route::is('admin') || Route::is('admin.*') ? 'active' : '' }}" id="home-tab"
                data-bs-toggle="tab" href="#tab-home" role="tab" aria-controls="tab-home" aria-selected="true"
                data-toggle="tooltip" title="Menu Dashboard">
                <i class="icon-home2"></i>
                {{-- <span class="nav-link-text">Dashboard</span> --}}
            </a>
            @if (Auth::user()->role != 6)
                <a class="nav-link {{ Route::is('penyetoran') ||Route::is('penyetoran.*') ||Route::is('tabungan') ||Route::is('tabungan.*') ||Route::is('iuran.kasir') ||Route::is('iuran.kasir.*') ||Route::is('harian') ||Route::is('harian.*')? 'active': '' }}"
                    id="kasir-tab" data-bs-toggle="tab" href="#tab-kasir" role="tab" aria-controls="tab-kasir"
                    aria-selected="false" data-toggle="tooltip" title="Menu Kasir">
                    <i class="icon-dollar-sign"></i>
                    {{-- <span class="nav-link-text">Kasir</span> --}}
                </a>
            @endif
            <a class="nav-link {{ Route::is('penjemputan') ||Route::is('penjemputan.*') ||Route::is('pengangkutan.terjadwal') ||Route::is('pengangkutan.terjadwal.*') ||Route::is('pengangkutan-penilaian') ||Route::is('pengangkutan-penilaian.*')? 'active': '' }}"
                id="pengajuan-tab" data-bs-toggle="tab" href="#tab-pengajuan" role="tab" aria-controls="tab-pengajuan"
                aria-selected="false" data-toggle="tooltip" title="Menu Pengangkutan Sampah">
                <i class="icon-truck"></i>
                {{-- <span class="nav-link-text">Pengajuan Penjemputan</span> --}}
            </a>
            <a class="nav-link {{ Route::is('rekapan-sampah') ||Route::is('rekapan-sampah.*') ||Route::is('rekapan-tabungan') ||Route::is('rekapan-tabungan.*') ||Route::is('rekapan-iuran') ||Route::is('rekapan-iuran.*') ||Route::is('rekapan-harian') ||Route::is('rekapan-harian.*') ||Route::is('tagihan') ||Route::is('tagihan.*') ||Route::is('list-tabungan') ||Route::is('list-tabungan.*') ||Route::is('rekapan-penilaian') ||Route::is('rekapan-penilaian.*')? 'active': '' }}"
                id="laporan-tab" data-bs-toggle="tab" href="#tab-laporan" role="tab" aria-controls="tab-laporan"
                aria-selected="false" data-toggle="tooltip" title="Menu Laporan">
                <i class="icon-printer"></i>
                {{-- <span class="nav-link-text">Laporan</span> --}}
            </a>
            <a class="nav-link {{ Route::is('statistik.keseluruhan') ||Route::is('statistik.keseluruhan.*') ||Route::is('statistik.harian') ||Route::is('statistik.harian.*')? 'active': '' }}"
                id=tabungan-tab" data-bs-toggle="tab" href="#tab-tabungan" role="tab" aria-controls="tab-tabungan"
                aria-selected="false" data-toggle="tooltip" title="Menu Statistik Sampah">
                <i class="icon-pie-chart1"></i>
                {{-- <span class="nav-link-text">Tabungan Nasabah</span> --}}
            </a>
            @if (Auth::user()->role == 1 || Auth::user()->role == 2)
                <a class="nav-link {{ Route::is('iuran.master') ||Route::is('iuran.master.*') ||Route::is('kategori') ||Route::is('kategori.*') ||Route::is('pelanggan') ||Route::is('pelanggan.*') ||Route::is('nasabah') ||Route::is('nasabah.*') ||Route::is('kriteria-penilaian') ||Route::is('kriteria-penilaian.*') ||Route::is('konfigurasi') ||Route::is('konfigurasi.*') ||Route::is('user') ||Route::is('user.*') ||Route::is('sistem.info') ||Route::is('sistem.info.*')? 'active': '' }}"
                    id="master-tab" data-bs-toggle="tab" href="#tab-master" role="tab" aria-controls="tab-master"
                    aria-selected="false" data-toggle="tooltip" title="Menu Master Data">
                    <i class="icon-database"></i>
                    {{-- <span class="nav-link-text">Master Data</span> --}}
                </a>
            @endif
            @if (Auth::user()->role != 6)
                <a class="nav-link {{ Route::is('artikel') || Route::is('artikel.*') ? 'active' : '' }}"
                    id=artikel-tab" data-bs-toggle="tab" href="#tab-artikel" role="tab" aria-controls="tab-artikel"
                    aria-selected="false" data-toggle="tooltip" title="Menu Artikel dan Produk">
                    <i class="icon-globe1"></i>
                    {{-- <span class="nav-link-text">Artikel</span> --}}
                </a>
                <a class="nav-link {{ Route::is('notifikasi') || Route::is('notifikasi.*') || Route::is('pengaduan') || Route::is('pengaduan.*')? 'active': '' }}"
                    id="notifikasi-tab" data-bs-toggle="tab" href="#tab-notifikasi" role="tab"
                    aria-controls="tab-notifikasi" aria-selected="false" data-toggle="tooltip" title="Menu Notifikasi">
                    <i class="icon-notifications_none"></i>
                    {{-- <span class="nav-link-text">Notifikasi</span> --}}
                </a>
            @endif
        </div>
        <!-- Tabs nav end -->

        <!-- Tabs content start -->
        <div class="tab-content">

            <!-- Dashboard tab -->
            <div class="tab-pane fade {{ Route::is('admin') || Route::is('admin.*') ? 'show active' : '' }}"
                id="tab-home" role="tabpanel" aria-labelledby="home-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Dashboard
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('admin') }}"
                                    class="{{ Route::is('admin') || Route::is('admin.*') ? 'current-page' : '' }}">Dashboard
                                    Admin</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->

            </div>

            @if (Auth::user()->role != 6)
                <!-- Kasir tab -->
                <div class="tab-pane fade {{ Route::is('penyetoran') ||Route::is('penyetoran.*') ||Route::is('tabungan') ||Route::is('tabungan.*') ||Route::is('iuran.kasir') ||Route::is('iuran.kasir.*') ||Route::is('harian') ||Route::is('harian.*')? 'show active': '' }}"
                    id="tab-kasir" role="tabpanel" aria-labelledby="kasir-tab">

                    <!-- Tab content header start -->
                    <div class="tab-pane-header">
                        Kasir
                    </div>
                    <!-- Tab content header end -->

                    <!-- Sidebar menu starts -->
                    <div class="sidebarMenuScroll">
                        <div class="sidebar-menu">
                            <ul>
                                <li>
                                    <a href="{{ route('penyetoran') }}"
                                        class="{{ Route::is('penyetoran') || Route::is('penyetoran.*') ? 'current-page' : '' }}">Kasir
                                        Nabung
                                        Sampah Nasabah</a>
                                </li>
                                <li>
                                    <a href="{{ route('tabungan') }}"
                                        class="{{ Route::is('tabungan') || Route::is('tabungan.*') ? 'current-page' : '' }}">Kasir
                                        Tarik
                                        Tabungan Nasabah</a>
                                </li>
                                <li>
                                    <a href="{{ route('iuran.kasir') }}"
                                        class="{{ Route::is('iuran.kasir') || Route::is('iuran.kasir.*') ? 'current-page' : '' }}">Kasir
                                        Pembayaran
                                        Iuran</a>
                                </li>
                                <li>
                                    <a href="{{ route('harian') }}"
                                        class="{{ Route::is('harian') || Route::is('harian.*') ? 'current-page' : '' }}">Kasir
                                        Rekap Sampah Harian</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Sidebar menu ends -->
                </div>
            @endif
            <!-- Tabungan tab -->
            <div class="tab-pane fade {{ Route::is('statistik.keseluruhan') ||Route::is('statistik.keseluruhan.*') ||Route::is('statistik.harian') ||Route::is('statistik.harian.*')? 'show active': '' }}"
                id="tab-tabungan" role="tabpanel" aria-labelledby="tabungan-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Statistik Sampah
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('statistik.keseluruhan') }}"
                                    class="{{ Route::is('statistik.keseluruhan') || Route::is('statistik.keseluruhan.*') ? 'current-page' : '' }}">Statistik
                                    Sampah Keseluruhan</a>
                            </li>
                            <li>
                                <a href="{{ route('statistik.harian') }}"
                                    class="{{ Route::is('statistik.harian') || Route::is('statistik.harian.*') ? 'current-page' : '' }}">Statistik
                                    Sampah Harian</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->
            </div>

            <!-- Pengajuan tab -->
            <div class="tab-pane fade {{ Route::is('penjemputan') ||Route::is('penjemputan.*') ||Route::is('pengangkutan.terjadwal') ||Route::is('pengangkutan.terjadwal.*') ||Route::is('pengangkutan-penilaian') ||Route::is('pengangkutan-penilaian.*')? 'show active': '' }}"
                id="tab-pengajuan" role="tabpanel" aria-labelledby="pengajuan-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Pengangkutan
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('penjemputan') }}"
                                    class="{{ Route::is('penjemputan') || Route::is('penjemputan.*') ? 'current-page' : '' }}">Data
                                    Permintaan Pengangkutan</a>
                            </li>
                            <li>
                                <a href="{{ route('pengangkutan.terjadwal') }}"
                                    class="{{ Route::is('pengangkutan.terjadwal') || Route::is('pengangkutan.terjadwal.*') ? 'current-page' : '' }}">Data
                                    Pengangkutan Terjadwal</a>
                            </li>
                            <li>
                                <a href="{{ route('pengangkutan-penilaian') }}"
                                    class="{{ Route::is('pengangkutan-penilaian') || Route::is('pengangkutan-penilaian.*') ? 'current-page' : '' }}">Pengangkutan
                                    dan Penilaian
                                    Harian</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->
            </div>

            <!-- Laporan tab -->
            <div class="tab-pane fade {{ Route::is('rekapan-sampah') ||Route::is('rekapan-sampah.*') ||Route::is('rekapan-tabungan') ||Route::is('rekapan-tabungan.*') ||Route::is('rekapan-iuran') ||Route::is('rekapan-iuran.*') ||Route::is('rekapan-harian') ||Route::is('rekapan-harian.*') ||Route::is('tagihan') ||Route::is('tagihan.*') ||Route::is('list-tabungan') ||Route::is('list-tabungan.*') ||Route::is('rekapan-penilaian') ||Route::is('rekapan-penilaian.*')? 'show active': '' }}"
                id="tab-laporan" role="tabpanel" aria-labelledby="laporan-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Laporan
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('rekapan-sampah') }}"
                                    class="{{ Route::is('rekapan-sampah') || Route::is('rekapan-sampah.*') ? 'current-page' : '' }}">Rekapan
                                    Nabung Sampah
                                    Nasabah</a>
                            </li>
                            <li>
                                <a href="{{ route('rekapan-tabungan') }}"
                                    class="{{ Route::is('rekapan-tabungan') || Route::is('rekapan-tabungan.*') ? 'current-page' : '' }}">Rekapan
                                    Penarikan
                                    Tabungan Nasabah</a>
                            </li>
                            <li>
                                <a href="{{ route('rekapan-iuran') }}"
                                    class="{{ Route::is('rekapan-iuran') || Route::is('rekapan-iuran.*') ? 'current-page' : '' }}">Rekapan
                                    Pembayaran
                                    Iuran</a>
                            </li>
                            <li>
                                <a href="{{ route('rekapan-harian') }}"
                                    class="{{ Route::is('rekapan-harian') || Route::is('rekapan-harian.*') ? 'current-page' : '' }}">Rekapan
                                    Sampah
                                    Harian</a>
                            </li>
                            <li>
                                <a href="{{ route('tagihan') }}"
                                    class="{{ Route::is('tagihan') || Route::is('tagihan.*') ? 'current-page' : '' }}">Rekapan
                                    Tagihan Iuran</a>
                            </li>
                            <li>
                                <a href="{{ route('list-tabungan') }}"
                                    class="{{ Route::is('list-tabungan') || Route::is('list-tabungan.*') ? 'current-page' : '' }}">Rekapan
                                    Tabungan
                                    Nasabah</a>
                            </li>
                            <li>
                                <a href="{{ route('rekapan-penilaian') }}"
                                    class="{{ Route::is('rekapan-penilaian') || Route::is('rekapan-penilaian.*') ? 'current-page' : '' }}">Rekapan
                                    Penilaian</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->
            </div>


            @if (Auth::user()->role == 1 || Auth::user()->role == 2)

                <!-- Master data tab -->
                <div class="tab-pane fade {{ Route::is('iuran.master') ||Route::is('iuran.master.*') ||Route::is('kategori') ||Route::is('kategori.*') ||Route::is('pelanggan') ||Route::is('pelanggan.*') ||Route::is('nasabah') ||Route::is('nasabah.*') ||Route::is('kriteria-penilaian') ||Route::is('kriteria-penilaian.*') ||Route::is('konfigurasi') ||Route::is('konfigurasi.*') ||Route::is('user') ||Route::is('user.*') ||Route::is('sistem.info') ||Route::is('sistem.info.*')? 'show active': '' }}"
                    id="tab-master" role="tabpanel" aria-labelledby="master-tab">

                    <!-- Tab content header start -->
                    <div class="tab-pane-header">
                        Master Data
                    </div>
                    <!-- Tab content header end -->

                    <!-- Sidebar menu starts -->
                    <div class="sidebarMenuScroll">
                        <div class="sidebar-menu">
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
                                <li>
                                    <a href="{{ route('konfigurasi') }}"
                                        class="{{ Route::is('konfigurasi') || Route::is('konfigurasi.*') ? 'current-page' : '' }}">Konfigurasi
                                        Dasar</a>
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

                            </ul>
                        </div>
                    </div>
                    <!-- Sidebar menu ends -->

                </div>
            @endif
            <!-- Artikel tab -->
            <div class="tab-pane fade {{ Route::is('artikel') || Route::is('artikel.*') ? 'show active' : '' }}"
                id="tab-artikel" role="tabpanel" aria-labelledby="artikel-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Artikel
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('artikel') }}"
                                    class="{{ Route::is('artikel') || Route::is('artikel.*') ? 'current-page' : '' }}">Data
                                    Artikel</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->
            </div>

            <!-- Notifikasi tab -->
            <div class="tab-pane fade {{ Route::is('notifikasi') || Route::is('notifikasi.*') || Route::is('pengaduan') || Route::is('pengaduan.*')? 'show active': '' }}"
                id="tab-notifikasi" role="tabpanel" aria-labelledby="notifikasi-tab">

                <!-- Tab content header start -->
                <div class="tab-pane-header">
                    Notifikasi
                </div>
                <!-- Tab content header end -->

                <!-- Sidebar menu starts -->
                <div class="sidebarMenuScroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li>
                                <a href="{{ route('notifikasi') }}"
                                    class="{{ Route::is('notifikasi') || Route::is('notifikasi.*') ? 'current-page' : '' }}">Manajemen
                                    Notifikasi</a>
                            </li>
                            <li>
                                <a href="{{ route('pengaduan') }}"
                                    class="{{ Route::is('pengaduan') || Route::is('pengaduan.*') ? 'current-page' : '' }}">Kritik,
                                    Pengaduan, dan Saran</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sidebar menu ends -->
            </div>
        </div>
        <!-- Tabs content end -->

    </div>
    <!-- Sidebar content end -->

</nav>