# Scheduler

### Scheduler App made with Laravel 5.7
---
## Specification
---
1.  [Laravel 5.7](https://laravel.com/docs/5.7/installation)
2.  [Composer](https://getcomposer.org/download/)
3.  Web Server (Apache/Nginx)
3.  PHP >= 7.1.3
4.  Mariadb 10.1.29

### Untuk server requirements, silahkan lihat [Laravel 5.7 Server Requirements](https://laravel.com/docs/5.7/installation)

## Configuration
1.  Isikan spesifikasi database pada .env file
2.  Untuk driver email, silahkan ganti line MAIL_DRIVER, MAIL_HOT, MAIL_PORT dll dalam file .env
3.  Jalankan perintah composer install untuk menginstall seluruh dependency dari aplikasi
4.  Setelah selesai konfigurasi file .env, jalankan perintah php artisan migrate --seed dari terminal untuk generate database (memerlukan composer terinstall pada server)
5.  Setelah migrate berhasil, buka link http://project-url/generate-adminuser untuk generate default admin user (username: admin/password:admin)
6.  Kemudian import data master dari tiap link
    -   Template tersedia di page menu dengan ekstensi file: .xlsx
    -   Untuk foreign key di file template, tidak menggunakan ID, tapi menggunakan slug. Slug bisa dilihat di tiap data

## Application Configuration
1.  Setting waktu di menu General Setting/Set Available Time
2.  Setting data master di menu Master

## Library Lists
1.  maatwebsite/excel versi 3.1
3.  elibyy/tcpdf-laravel versi 5.7
4.  guzzlehttp/guzzle versi 6.3

## Front End Library
1.  JQuery versi 3.2
2.  Bootstap versi 4.0
3.  CoreUI versi 2.1.7
4.  Fontawesome versi 5.7.2
5.  Sweet Alert versi 2.1.2	