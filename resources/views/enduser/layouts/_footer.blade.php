<div class="footer">
    <div class="footer-container">
        <div class="col-3 footer-item">

            <a href=" {{ route('enduser.tagihan.index') }} "
                class="{{ Route::is('enduser.tagihan.index') || Route::is('enduser.tagihan.*') || Route::is('enduser.riwayat.*') ? 'active' : '' }}">
                <i class="fas fa-money-check-alt"></i>
                <span>Tagihan</span>
            </a>
        </div>
        <div class="col-3 footer-item">
            <a href="{{ route('enduser.dashboard') }}" class="{{ Route::is('enduser.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </div>
        <div class="col-3 footer-item">
            <a href="{{ route('enduser.profil.index') }}"
                class="{{ Route::is('enduser.profil.index') || Route::is('enduser.profil.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        </div>
    </div>
</div>
