<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use GuzzleHttp\Client;
use App\Models\SystemNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Route::is('enduser.validasi.token.rekening') == false && Route::is('enduser.validasi.token.aksi') == false && Route::is('enduser.validasi.token.phone') == false) {
                $session_data = Session::get('request_change');
                if (! empty($session_data)) {
                    Session::forget('request_change');
                    Session::forget('check_spam');
                }
            }
            return $next($request);
        });
    }
    protected function removeSessionChangeProfil()
    {
        if (Route::is('ganti.profil.validasi') == false && Route::is('ganti.profil.validasi.aksi') == false) {
            $session_data = Session::get('request_change');
            if (! empty($session_data)) {
                Session::forget('request_change');
                Session::forget('check_spam');
            }
        }
    }

    protected function storeNotification($id_user, $key, $title, $message)
    {
        $pengelola_pegawai = User::where('role', '1')->orWhere('role', '2')->orWhere('role', '3')->get();
        if ($key != 'notif') {
            foreach ($pengelola_pegawai as $user) {
                // Create For Pengelola Pegawai
                SystemNotification::create([
                    'id' => Uuid::uuid4(),
                    'user_id' => $user->id,
                    'key' => $key,
                    'judul' => $title,
                    'konten' => $message,
                ]);
            }
        }
        if ($id_user != 'null') {
            // For Single User
            SystemNotification::create([
                'id' => Uuid::uuid4(),
                'user_id' => $id_user,
                'key' => $key,
                'judul' => $title,
                'konten' => $message,
            ]);
        }
    }
    protected function removeSessionTmpAll()
    {
        $data = DB::transaction(function () {
            Session::forget('tmp_rekapan_sampah');
            Session::forget('tmp_tabungan');
            Session::forget('tmp_rekapan_harian');
            Session::forget('tmp_tagihan_iuran');
        });
        return $data;
    }

    // Mitra
    public static function getMitra()
    {
        try {
            $data = [
                'id' => config('app.api_key_mitra'),
            ];
            $url = config('app.api_url_mitra') . '/mitra/getOne';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode($data),
                ]
            );

            $body = json_decode($response->getBody(), true);
            return $body;
        } catch (Exception $e) {
            $info = [
                'meta' => [
                    'code' => '500',
                    'message' => 'Fitur ini sedang dalam perbaikan',
                ],
            ];
            return $info;
        }
    }

    // WhatsApp Sending Message Using API Point
    public static function sendMessage($receiver, $message)
    {
        try {
            $data = [
                'no_hp' => '62' . $receiver, //include string 62 to the front of user's phone number
                'pesan' =>  $message,
            ];
            $url = config('app.api_notification') . '/wa-message';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode($data),
                ]
            );

            $body = json_decode($response->getBody(), true);
            return $body;
        } catch (Exception $e) {
            $info = [
                'kode' => '500',
                'message' => 'Fitur ini sedang dalam perbaikan',
            ];
            return $info;
        }
    }

    public static function sendMessageWithFile($receiver, $file, $message)
    {
        try {
            $data = [
                'no_hp' => '62' . $receiver, //include string 62 to the front of user's phone number
                'pesan' =>  $message,
                'link'    => $file,
            ];
            $url = config('app.api_notification') . '/wa-media';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);
            return $body;
        } catch (Exception $e) {
            $info = [
                'kode' => '500',
                'message' => 'Fitur ini sedang dalam perbaikan',
            ];
            return $info;
        }
    }

    public static function sendEmail($to, $subject, $text)
    {
        try {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'text' => $text,
            ];
            $url = config('app.api_notification') . '/email-message';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);
            return $body;
        } catch (Exception $e) {
            $info = [
                'kode' => '500',
                'message' => 'Fitur ini sedang dalam perbaikan',
            ];
            return $info;
        }
    }

    public static function sendEmailWithFile($to, $subject, $text, $filename, $link)
    {
        try {
            $data = [
                'to' => $to,
                'subject' => $subject,
                'text' => $text,
                'filename' => $filename,
                'link' => $link,
            ];
            $url = config('app.api_notification') . '/email-media';
            $client = new Client();
            $response = $client->request(
                'POST',
                $url,
                [
                    // don't forget to set the header
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body'    => json_encode($data),
                ]
            );
            $body = json_decode($response->getBody(), true);
            return $body;
        } catch (Exception $e) {
            $info = [
                'kode' => '500',
                'message' => 'Fitur ini sedang dalam perbaikan',
            ];
            return $info;
        }
    }

    // WhatsApp Global Variabel
    public static function message_otp($name, $token, $expired)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $name . " , Kode OTP anda adalah [ *$token* ]. Kode OTP aktif sampai " . Carbon::parse($expired)->format('d F Y H:i') . " WITA. Kode OTP bersifat rahasia, jangan bagikan kepada siapapun! \n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_invoice($name)
    {
        $message = "[Semesta Kita]\n\nHalo " . $name . ', berikut adalah invoice untuk pembayaran tagihan iuran anda. Terimakasih.' . "\n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_pengangkutan($pelanggan, $tanggal, $pegawai)
    {
        $message = "[Semesta Kita]\n\nHalo $pelanggan , Sampah Anda telah diangkut pada " . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA oleh ' . $pegawai . ". Ingat selalu untuk memilah sampah Anda sebelum diangkut oleh petugas!\n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_tagihan($pelanggan, $total_tagihan, $bulan, $tanggal_terbayar)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $pelanggan . ', Tagihan Iuran bulanan sebesar Rp.' . number_format($total_tagihan, 2, ',', '.') . ' untuk bulan ' . \Carbon\Carbon::parse($bulan)->format('F Y') . ' telah berhasil dibayarkan pada  ' . \Carbon\Carbon::parse($tanggal_terbayar)->format('d F Y H:i') . " WITA. Terimakasih telah membayar tepat waktu!\n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_tagihan_baru($pelanggan, $deskripsi, $bulan, $total_tagihan)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $pelanggan . ', Tagihan baru *' . $deskripsi . '* untuk bulan ' . \Carbon\Carbon::parse($bulan)->format('F Y') . ' sebesar Rp.' . number_format($total_tagihan, 2, ',', '.') . " telah dibuat untuk Anda. Segera lakukan pembayaran! \n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_notifikasi($pegawai, $judul, $konten)
    {
        $message =
            "[Semesta Kita]\n\nInformasi dari " . $pegawai . "\n\nJudul: _\"" . strtoupper($judul) . "\"_\nIsi Pesan: _\"" . ucWords(strip_tags($konten)) . "\"_ \n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_penyetoran($pelanggan, $tanggal, $saldo_masuk, $total_saldo)
    {
        $message = "[Semesta Kita]\n\nHalo " . $pelanggan . ',Penyetoran sampah sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Anda menerima tambahan saldo sebesar Rp.' . number_format($saldo_masuk, 2, ',', '.') . ' dari ' . config('mitra.name') . ' .Total saldo tabungan anda saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . "  \n\n" . config('mitra.name');
        return $message;
    }

    public static function message_profil($pelanggan, $no_hp_baru, $token, $expired)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $pelanggan . ', Kami menerima permintaan pergantian nomor WhatsApp Anda ke nomor baru 0' . $no_hp_baru . ". \n\nUntuk memvalidasi bahwa itu benar anda, masukkan Kode OTP berikut [ *$token* ], Kode OTP aktif sampai " . Carbon::parse($expired)->format('d M Y H:i') . " WITA. \n\nKode OTP bersifat rahasia, jangan bagikan kepada siapapun. Abaikan pesan ini jika permintaan ini bukan dari Anda! \n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_penarikan($pelanggan, $tanggal, $total_tarik, $total_saldo)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $pelanggan . ',Penarikan tabungan Anda sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Saldo Anda ditarik sebesar Rp.' . number_format($total_tarik, 2, ',', '.') . ' oleh ' . config('mitra.name') . ' .Total saldo Anda saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . "  \n\n" . config('mitra.name') . '';
        return $message;
    }

    public static function message_rekening($pelanggan, $bank, $rekening, $token, $expired)
    {
        $message =
            "[Semesta Kita]\n\nHalo " . $pelanggan . ', Kami menerima pergantian nomor rekening Anda ke rekening bank ' . $bank . ' dengan nomor ' . $rekening . ". Untuk memvalidasi bahwa itu benar anda, masukkan Kode OTP berikut [ *$token* ], Kode OTP aktif sampai " . Carbon::parse($expired)->format('d M Y H:i') . " WITA. \n\nKode OTP bersifat rahasia, jangan bagikan kepada siapapun. Abaikan pesan ini jika permintaan bukan dari Anda ! \n\n" . config('mitra.name') . '';
        return $message;
    }

    // Web Notification Global Variabel
    public static function notif_tagihan($pelanggan, $total_tagihan, $bulan, $tanggal_terbayar)
    {
        $pesan_notif = 'Tagihan Iuran bulanan ' . $pelanggan . ' sebesar Rp.' . number_format($total_tagihan, 2, ',', '.') . ' untuk bulan ' . \Carbon\Carbon::parse($bulan)->format('F Y') . ' telah berhasil dibayarkan pada  ' . \Carbon\Carbon::parse($tanggal_terbayar)->format('d F Y H:i') . ' WITA. Terimakasih telah membayar tepat waktu!';
        return $pesan_notif;
    }

    public static function notif_pengangkutan($tanggal, $pegawai)
    {
        $pesan_notif = 'Sampah Anda telah diangkut pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA oleh ' . $pegawai . '. Ingat selalu untuk memilah sampah Anda sebelum diangkut oleh petugas!';
        return $pesan_notif;
    }

    public static function notif_penyetoran($tanggal, $pelanggan, $total_masuk, $total_saldo)
    {
        $pesan_notif = 'Penyetoran sampah sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Saldo masuk untuk ' . $pelanggan . ' sebesar Rp.' . number_format($total_masuk, 2, ',', '.') . ' dari ' . config('mitra.name') . ' .Total saldo saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . '';
        return $pesan_notif;
    }

    public static function notif_penarikan($pelanggan, $tanggal, $total_tarik, $total_saldo)
    {
        $pesan_notif =
            'Penarikan Tabungan  ' . $pelanggan . ' sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Saldo ditarik sebesar Rp.' . number_format($total_tarik, 2, ',', '.') . ' oleh ' . config('mitra.name') . ' .Total saldo saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . '';
        return $pesan_notif;
    }
    // Email Notification Global Variabel
    public static function email_tagihan($total_tagihan, $bulan, $tanggal_terbayar, $email, $name)
    {
        $konten = 'Tagihan Iuran bulanan sebesar Rp.' . number_format($total_tagihan, 2, ',', '.') . ' untuk bulan ' . \Carbon\Carbon::parse($bulan)->format('F Y') . ' telah berhasil dibayarkan pada  ' . \Carbon\Carbon::parse($tanggal_terbayar)->format('d F Y H:i') . ' WITA. Terimakasih telah membayar tepat waktu!';
        $judul = 'Pembayaran Tagihan Iuran Bulanan';
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmail($email, $judul, $message);
        return $send;
    }

    public static function email_tagihan_baru($to, $deskripsi, $bulan, $total_tagihan, $user)
    {
        $judul = 'Tagihan Iuran Bulanan Baru';
        $message = view('email.tagihan-baru', compact('deskripsi', 'bulan', 'total_tagihan', 'user'))->render();
        $send =  Controller::sendEmail($to, $judul, $message);
        return $send;
    }

    public static function email_security($to, $link, $name)
    {
        $judul = 'Security Code';
        $message = view('email.securityNotification', compact('link', 'name'))->render();
        $send =  Controller::sendEmail($to, $judul, $message);
        return $send;
    }

    public static function email_reset($to, $link, $name)
    {
        $judul = 'Reset Password Notification';
        $message = view('email.securityResetPassword', compact('link', 'name'))->render();
        $send =  Controller::sendEmail($to, $judul, $message);
        return $send;
    }

    public static function email_otp($to, $otp, $name, $expired_date)
    {
        $judul = 'Kode OTP WhatsApp';
        $expired = Carbon::parse($expired_date)->format('d F Y H:i');
        $message = view('email.notifikasi_otp', compact('otp', 'name', 'expired'))->render();
        $send =  Controller::sendEmail($to, $judul, $message);
        return $send;
    }

    public static function email_pengangkutan($tanggal, $pegawai, $email, $name)
    {
        $konten = 'Sampah Anda telah diangkut pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA oleh ' . $pegawai . '. Ingat selalu untuk memilah sampah Anda sebelum diangkut oleh petugas!';
        $judul = 'Pengangkutan Sampah Harian';
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmail($email, $judul, $message);
        return $send;
    }

    public static function email_notifikasi_file($email, $name, $konten, $judul, $link, $filename)
    {
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmailWithFile($email, $judul, $message, $filename, $link);
        return $send;
    }

    public static function email_notifikasi($email, $name, $konten, $judul)
    {
        $judul = 'Email Notifikasi dari Admin';
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmail($email, $judul, $message);
        return $send;
    }

    public static function email_penyetoran($tanggal, $saldo_masuk, $total_saldo, $email, $name)
    {
        $konten = 'Penyetoran sampah sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Anda menerima saldo sebesar Rp.' . number_format($saldo_masuk, 2, ',', '.') . ' dari ' . config('mitra.name') . ' .Total saldo anda saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . '';
        $judul = 'Penyetoran Tabungan Sampah';
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmail($email, $judul, $message);
        return $send;
    }

    public static function email_penarikan($tanggal, $total_tarik, $total_saldo, $email, $name)
    {
        $konten = 'Penarikan Tabungan sukses dilakukan pada ' . \Carbon\Carbon::parse($tanggal)->format('d F Y H:i') . ' WITA. Saldo anda ditarik sebesar Rp.' . number_format($total_tarik, 2, ',', '.') . ' oleh ' . config('mitra.name') . ' .Total saldo anda saat ini adalah Rp.' . number_format($total_saldo, 2, ',', '.') . '';
        $judul = 'Penarikan Tabungan Sampah';
        $message = view('email.notifikasi', compact('name', 'konten', 'judul'))->render();
        $send =  Controller::sendEmail($email, $judul, $message);
        return $send;
    }
}
