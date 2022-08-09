<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'users';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'foto',
        'no_telp',
        'no_member',
        'nama_bank',
        'alamat',
        'pembayaran_rutin_id',
        'name',
        'no_rekening',
        'email',
        'password',
        'password_whatsapp',
        'role',
        'status_iuran',
        're_email',
        're_expired',
        're_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'no_rekening',
        // 'no_member',
        'password_whatsapp',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFotoAttribute()
    {
        if ($this->attributes['foto'] == null || $this->attributes['foto'] == asset('assets/admin/img/default-user.svg') || $this->attributes['foto'] == asset('assets/admin/img/default-user-new.png')) {
            // return $this->attributes['foto'] = config('app.url') . Storage::url('public/no_image.png');
            return $this->attributes['foto'] = asset('assets/admin/img/default-user-new.png');
        }
        $path = $this->attributes['foto'];
        if (str_contains($path, '/')) {
            return config('app.url') . Storage::url('public/' . $path);
        }
        return config('app.url') . Storage::url('public/users/' . $path);

        // return asset('assets/' . $this->attributes['foto']);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     *
     * @param mixed $identifier
     */
    public function findForPassport($identifier)
    {
        return $this->orWhere('email', $identifier)->orWhere('no_telp', $identifier)->first();
    }

    // Relationalship
    public function pembayaran_rutin()
    {
        return $this->belongsTo(PembayaranRutin::class);
    }

    public function rekapan_sampah()
    {
        return $this->hasMany(RekapanSampah::class);
    }

    public function rekapan_penarikan_tabungan()
    {
        return $this->hasMany(RekapanPenarikanTabungan::class);
    }

    public function log_activity()
    {
        return $this->hasMany(LogActivity::class);
    }

    public function tabungan()
    {
        return $this->hasOne(Tabungan::class);
    }

    public function tmp_tagihan_iuran()
    {
        return $this->hasOne(TmpTagihanIuran::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function system_notifikasi()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function pengaduan_user()
    {
        return $this->hasMany(PengaduanUser::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function rekapan_iuran()
    {
        return $this->hasMany(RekapanIuran::class);
    }

    public function tagihan_iuran()
    {
        return $this->hasMany(TagihanIuran::class, 'user_id', 'id');
    }

    public function pengangkutan_penilaian_harian()
    {
        return $this->hasMany(PengangkutanPenilaianHarian::class, 'user_id', 'id', 'pegawai_id', 'id');
    }

    public function rekapan_penilaian()
    {
        return $this->hasMany(RekapanPenilaian::class, 'user_id', 'id');
    }

    public function rekomendasi()
    {
        return $this->hasMany(Rekomendasi::class, 'user_id', 'id');
    }

    public function pemilah_aktif()
    {
        return $this->hasMany(PemilahAktif::class, 'user_id', 'id');
    }
}
