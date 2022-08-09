<p align="center"><a href="https://semestakita.id" target="_blank"><img src="https://email.semestakita.id/icon-text.png" width="400"></a></p>

<p align="center">
<img alt="php" src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/jQuery-0769AD?style=for-the-badge&logo=jquery&logoColor=white"> <img alt="bootstrap" src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white">
</p>

## SEMESTA KITA

Semesta kita merupakan sebuah aplikasi berbasis web yang dapat digunakan untuk membantu dalam memanajemen TPST yang ada, sistem ini terdiri dari fitur Kasir tabungan, pembayaran tagihan, penyetoran sampah, hingga rekapan dan laporan yang akan membantu TPST dalam memanajamen kegiatan transaksinya dengan mudah, cepat, dan berbasis teknologi

## System Requerements

-   PHP >= 8.0
-   [GIT Windows](https://git-scm.com/download/win)
-   [Composer](https://getcomposer.org/download/)
-   Apache Server dan SQL Server => [Dapat diperoleh dengan menginstall [XAMPP](https://www.apachefriends.org/download.html) atau [Laragon](https://laragon.org/download/index.html)]
-   [intl](http://php.net/manual/en/intl.requirements.php)
-   [libcurl](http://php.net/manual/en/curl.requirements.php)

Selanjutnya, pastikan pada `php.ini` anda telah mengaktifkan:

-   json (enabled by default - don't turn it off)
-   [mbstring](http://php.net/manual/en/mbstring.installation.php)
-   [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
-   xml (enabled by default - don't turn it off)
-   imagick

## Installation & updates

-   Buka folder `xampp/htdocs` atau `laragon/www` lalu clone repository ini
-   Buka folder `penelitian-dan-pkm-2021` di Visual Studio Code
-   Buat sebuah database di mysql,menggunakan phpmyadmin. Selanjutnya rename file `.env.rename` menjadi `.env` lalu sesuaikan nama database di `.env` dengan database yang telah dibuat
-   Buka terminal/cmd, arahkan ke folder root project. Jalankan perintah `composer update`. Setelah itu, jalankan perintah berikut secara bertahap

1. `php artisan migrate --seed`
2. `php artisan passport:install` untuk digunakan dalam send WhatsApp
3. `php artisan serve`

-   Jika tidak ada masalah, silahkan akses kehalaman `http://127.0.0.1/8000` maka seharusnya halaman awal SEMESTA TRIDATU sudah terlihat

## Account Information

-   Untuk login ke sistem sebagai super admin gunakan email `super.admin@tridatu.id` dengan kata sandi `12345678`
-   Untuk login ke sistem sebagai pengelola gunakan email `pengelola@tridatu.id` dengan kata sandi `12345678`
-   Untuk login ke sistem sebagai tamu gunakan email `tamu@tridatu.id` dengan kata sandi `12345678`
-   Untuk login ke sistem sebagai pegawai gunakan email `pegawai@tridatu.id` dengan kata sandi `12345678`
-   Untuk login ke sistem sebagai nasabah gunakan email `nasabah@tridatu.id` dengan kata sandi `12345678`
-   Untuk login ke sistem sebagai pelanggan gunakan email `pelanggan@tridatu.id` dengan kata sandi `12345678`

## Kode Role di Table User Database

-   Role 1 : Super Admin
-   Role 2 : Pengelola
-   Role 3 : Pegawai
-   Role 4 : Nasabah
-   Role 5 : Pelanggan
-   Role 6 : Tamu

## Notice

Sebelum melakukan pull atau sebelum melakukan push, mohon melakukan reformat dengan syntax `./vendor/bin/pint`, Kemudian silahkan lakukan git add dan commit kembali, lalu dapat melakukan push

## Laravel Information

Create using Laravel 8, list of library :

-   Laravel UI
-   Laravel Pint
-   Ramsey UUID
-   Import Export
-   DOMPDF
-   Laravel Passport
-   Laravel OTP
-   Code Scanner Library

## Contributing

-   Tim Pengembang Undiksha
-   GanaTech ID

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
