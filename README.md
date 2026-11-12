# Catering Nyonya Surya

Sistem pemesanan catering berbasis Laravel 13, PHP 8.4, Eloquent, dan Blade, mengikuti proposal Sistem Informasi Pemesanan Catering Nyonya Surya. Dashboard menggunakan stylesheet Volt Bootstrap 5 dari Themesberg; lisensi disertakan di public/vendor/volt/LICENSE.md.

## Menjalankan aplikasi

Prasyarat: PHP 8.4+, Composer, ekstensi PDO SQLite atau PDO MySQL, mbstring, fileinfo, dan GD.

```sh
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Di PowerShell, gunakan `Copy-Item .env.example .env`. Untuk SQLite, buat file database/database.sqlite jika belum tersedia: `New-Item -ItemType File database/database.sqlite`.

Buka http://127.0.0.1:8000. Semua halaman fitur memerlukan login. CSS, JavaScript, dan ilustrasi tersedia lokal di public; aplikasi tidak memerlukan npm atau koneksi CDN untuk ditampilkan.

## Akun contoh lokal

Seeder hanya membuat akun contoh ketika APP_ENV=local atau testing:

| Peran | Email | Username | Kata sandi |
| --- | --- | --- | --- |
| Admin | admin@nyonyasurya.test | admin | SuryaDemo2026! |
| Pelanggan | pelanggan@nyonyasurya.test | — | SuryaDemo2026! |

Data menu dan harga pada seeder adalah contoh untuk demonstrasi, bukan daftar harga resmi usaha. Gambar bawaan adalah ilustrasi; admin dapat menggantinya dengan foto melalui form menu. Seeder tidak menimpa menu dan akun yang sudah ada.

Untuk membuat akun admin khusus, jalankan `php artisan catering:admin`. Kata sandi diminta secara tersembunyi. Registrasi website selalu membuat akun pelanggan.

## MySQL sesuai proposal

Buat database kosong bernama catering_nyonya_surya di MySQL/phpMyAdmin dan atur .env:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=catering_nyonya_surya
DB_USERNAME=root
DB_PASSWORD=isi_sesuai_konfigurasi_mysql
```

Lalu jalankan `php artisan config:clear` dan `php artisan migrate --seed`. Peralihan koneksi tidak memindahkan data SQLite ke MySQL. Konfigurasi lokal sementara memakai SQLite karena koneksi MySQL awal ditolak.

Jika menggunakan Apache/XAMPP, arahkan DocumentRoot virtual host ke direktori public proyek. Jangan arahkan DocumentRoot ke direktori akar yang berisi .env.

## Fitur

- Login melalui email atau username, registrasi pelanggan, logout, pembatasan percobaan login, dan middleware admin.
- Beranda, informasi layanan, katalog dengan pencarian, filter kategori, paginasi, dan detail paket.
- Keranjang multi-paket, ubah jumlah, hapus paket, subtotal dan total dari harga database.
- Checkout nama, telepon, alamat, tanggal acara, dan catatan; validasi server; transaksi database dan pencegahan pengiriman ganda.
- Riwayat dan detail pesanan milik pelanggan, dengan status Menunggu, Diproses, Selesai, atau Dibatalkan.
- Dashboard admin, CRUD kategori dan paket, unggah JPG/PNG/WebP maksimum 2 MB, ketersediaan menu, pencarian/filter pesanan, dan perubahan status.
- Harga dan nama paket disalin ke detail pesanan supaya perubahan atau penghapusan menu tidak mengubah riwayat.
- Kategori yang masih memiliki menu tidak dapat dihapus. Gambar diakses melalui route terautentikasi tanpa memerlukan storage:link.

## Pengujian

```sh
php artisan test --compact
php artisan view:cache
php vendor/bin/pint --format agent
```

Tes menggunakan SQLite in-memory, terpisah dari database lokal. GitHub Actions menjalankan tes otomatis saat push dan pull request.

## Menyimpan di GitHub

Repositori lokal menyertakan source, composer.lock, aset antarmuka, migrasi, seeder, dan pengujian. .env, vendor, database SQLite, gambar unggahan, dan alat pembaca proposal diabaikan Git.

Buat repositori kosong bernama catering-nyonya-surya di akun GitHub Anda, lalu hubungkan dan kirim:

```sh
git remote add origin https://github.com/USERNAME/catering-nyonya-surya.git
git push -u origin main
```

Jika origin sudah ada, gunakan `git remote set-url origin URL_REPOSITORI`. Untuk menyimpan perubahan selanjutnya:

```sh
git add .
git commit -m "Jelaskan perubahan"
git push
```

Penyimpanan di GitHub menyimpan kode; hosting Laravel dan backup database dikelola terpisah.
