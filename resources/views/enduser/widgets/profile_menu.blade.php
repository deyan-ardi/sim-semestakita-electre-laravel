<div class="page-header">
    <div class="row">
        <div class="col-12">
            <div class="widget settings-menu profile-page">
                <ul>
                    <li class="nav-item">
                        <a href="{{ route('enduser.profil.index') }}"
                            class="nav-link {{ Route::is('enduser.profil.index') || Route::is('enduser.validasi.token.phone')? 'active' : 'text-muted' }}">
                            <i class="far fa-user"></i> <span>Informasi Akun</span>
                        </a>
                    </li>
                    @if (Auth::user()->role == 4)
                    <li class="nav-item">
                        <a href="{{ route('enduser.profil.rekening') }}"
                            class="nav-link {{ Route::is('enduser.profil.rekening') || Route::is('enduser.validasi.token.rekening') ? 'active' : 'text-muted' }}">
                            <i class="fas fa-credit-card"></i> <span>Informasi Rekening</span>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('enduser.profil.security') }}"
                            class="nav-link {{ Route::is('enduser.profil.security') ? 'active' : 'text-muted' }}">
                            <i class="fas fa-unlock-alt"></i> <span>Keamanan Akun</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a href="{{ route('enduser.profil.change_pass') }}" class="nav-link text-muted">
                    <i class="fas fa-unlock-alt"></i> <span>Ubah Kata Sandi</span>
                    </a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
</div>