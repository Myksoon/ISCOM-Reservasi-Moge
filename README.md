# Reservasi Sewa Moge Surabaya

Aplikasi Laravel untuk mencatat booking sewa motor gede. Dibuat untuk tugas ISCOM.

Website: https://iscom-reservasi-moge.vercel.app

## Tentang website

Website ini dipakai untuk rental moge di Surabaya. Pengunjung bisa melihat motor
yang tersedia beserta harga sewanya, lalu membuat booking tanpa perlu login. Admin
login untuk melihat daftar booking, mengubah status, dan menghapus data.

Ada tiga halaman utama:

- **Beranda** (`/`): daftar motor, harga sewa per hari, dan cara sewa. Tombol
  "Sewa motor ini" langsung membuka form booking dengan motor yang dipilih.
- **Booking baru** (`/bookings/create`): form untuk mengisi nama, nomor WhatsApp,
  motor, jadwal ambil, dan jadwal kembali.
- **Daftar booking** (`/bookings`, khusus admin): semua booking, bisa dicari berdasarkan nama,
  nomor HP, atau motor, dan bisa difilter per status. Nomor HP bisa diklik untuk
  langsung chat WhatsApp.

Setiap booking punya kode seperti plat nomor, misalnya `L 0012 MG`.

## Login admin

Halaman login ada di `/login`, linknya di bagian bawah website.

Akun admin untuk testing:

```
Username: agus
Password: 123
```

Akun ini hanya untuk testing. Sebelum website dipakai untuk booking sungguhan,
ganti password-nya.

Tidak ada halaman daftar akun, jadi akun admin dibuat lewat seeder:

```
php artisan db:seed
```

Untuk mengganti username atau password, isi `ADMIN_USERNAME` dan `ADMIN_PASSWORD`
di `.env`, lalu jalankan seeder lagi.

## Cara kerja booking

- Satu motor tidak bisa dipesan dua orang di jam yang sama. Kalau jadwalnya
  bertabrakan, form akan menolak dan memberi tahu motor itu dipesan sampai kapan.
- Jam kembali harus setelah jam mulai sewa.
- Status berubah sendiri sesuai waktu: **menunggu** sebelum jadwal mulai,
  **disewa** saat jadwal berjalan, dan **selesai** setelah jam kembali lewat.
- Status **delay**, **reschedule**, dan **batal** diubah manual oleh admin lewat
  halaman edit. Booking yang batal atau selesai tidak menghalangi booking lain.

Daftar motor dan harganya ada di `config/moge.php`. Kalau mau menambah motor,
cukup tambah satu data di file itu dan taruh fotonya di `public/images`.

Tombol **Akun** di pojok kanan atas membuka panel akun. Pengunjung bisa langsung
menuju halaman login, sedangkan admin yang sudah login melihat jumlah booking yang
menunggu konfirmasi beserta link ke daftar booking.

## Menjalankan di Laragon

1. Taruh folder project di `C:\laragon\www`, lalu nyalakan Apache dan MySQL.
2. Buat database `sistem_booking`.
3. Jalankan di terminal Laragon:

```
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
```

4. Buka `http://iscom-reservasi-moge.test` atau jalankan `php artisan serve`.

Untuk menjalankan test: `php artisan test`

Kalau tidak pakai MySQL (misalnya di Linux), bisa pakai SQLite. Ubah
`DB_CONNECTION=sqlite` di `.env`, buat file kosong `database/database.sqlite`,
lalu jalankan `php artisan migrate`.

## Deploy ke Vercel

Konfigurasinya ada di `vercel.json` dan `api/index.php`. Di pengaturan project
Vercel, pilih Framework Preset **Other** dan kosongkan Build Command.

Vercel tidak menyediakan database, jadi kami pakai TiDB Cloud (kompatibel dengan
MySQL). Isi Environment Variables di Vercel:

```
APP_KEY=
APP_URL=https://iscom-reservasi-moge.vercel.app
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=4000
DB_DATABASE=sistem_booking
DB_USERNAME=
DB_PASSWORD=
MYSQL_ATTR_SSL_CA=/etc/pki/tls/certs/ca-bundle.crt
```

`APP_KEY` dibuat dengan `php artisan key:generate --show`. Host, username, dan
password diambil dari menu Connect di TiDB Cloud.

Tabel dan akun admin dibuat sekali dari laptop dengan menjalankan
`php artisan migrate --seed` memakai pengaturan database TiDB di atas. Setelah mengubah Environment Variables, lakukan
Redeploy di Vercel.

## Kredit

Foto motor diambil dari Wikimedia Commons:

- Kawasaki Ninja ZX-6R oleh Sound Media (CC0)
- Harley-Davidson Iron 883 oleh Alek Kirstein (CC BY-SA 2.0)
- Ducati Panigale V2 oleh Terragio67 (CC BY-SA 4.0)
- Yamaha YZF-R1M oleh Dennis Bratland (CC BY-SA 4.0)
- Honda CBR1000RR-R oleh Chanokchon (CC BY-SA 4.0)
