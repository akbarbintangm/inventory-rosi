# Inventory ROSI

Sistem manajemen persediaan PT ABC berbasis Laravel 10 dan MySQL. Aplikasi mencakup POS, pesanan pelanggan, pembelian, quotation, produk, bahan baku, pelanggan, pemasok, laporan, serta kontrol akses berbasis role dan permission.

## Role dan permission

Aplikasi memiliki tiga role sesuai rancangan skripsi:

- `owner`: akses penuh, termasuk manajemen pengguna, penghapusan master data, dan persetujuan pembelian.
- `manager_pic`: mengelola produk, bahan baku, pemasok, pembelian, quotation, dan laporan tanpa hak hapus atau persetujuan pembelian.
- `admin`: mengelola pelanggan, POS/pesanan, nota terima, kategori, dan satuan; serta dapat melihat data persediaan dan laporan umum.

Hak akses diperiksa pada route/controller dan elemen antarmuka. Role lama pada kolom `users.level` disinkronkan oleh `RolePermissionSeeder` agar kompatibel dengan data yang sudah ada.

## Audit stok dan peringatan stok minimum

Setiap perubahan stok produk atau bahan baku dicatat pada tabel `stock_mutations`, termasuk stok sebelum/sesudah, jumlah perubahan, tipe transaksi, referensi transaksi, catatan, dan pengguna pelaksana. Persetujuan pembelian serta penyelesaian/cancel transaksi dibuat idempoten agar stok tidak berubah dua kali.

Peringatan stok minimum dapat dijalankan secara manual:

```bash
php artisan inventory:low-stock-alert
```

Gunakan `--dry-run` untuk memeriksa jumlah produk di bawah batas tanpa mengirim email. Scheduler menjalankan pemeriksaan setiap hari pukul 08.00; pada server produksi, aktifkan Laravel scheduler melalui cron atau task scheduler.

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Atur koneksi database dan konfigurasi email pada `.env` sebelum migrasi. Akun awal dari seeder menggunakan email `admin@admin.com` dan password `password`; ubah password setelah login pertama.

Jika aplikasi lama sudah memiliki tabel dan pengguna, jalankan:

```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
php artisan optimize:clear
```

## Pengujian

```bash
php artisan test
```

Test mencakup autentikasi, CRUD utama, matriks role/permission, pencatatan mutasi stok, pencegahan stok negatif, dan idempotensi persetujuan pembelian.

## Lisensi

Proyek ini mempertahankan lisensi [MIT](LICENSE) dari aplikasi dasar.
