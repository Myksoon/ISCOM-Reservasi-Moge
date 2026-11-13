# Reservasi Sewa Moge Surabaya

Aplikasi Laravel untuk mencatat booking sewa motor gede. Dibuat untuk tugas ISCOM.

## Fitur

- Halaman beranda berisi daftar armada dan cara sewa
- Tambah, ubah, hapus booking
- Pilih motor dari daftar armada (`config/moge.php`)
- Booking ditolak kalau motor yang sama sudah dipesan di jadwal yang bertabrakan
- Status berubah otomatis: menunggu, disewa, selesai
- Cari booking berdasarkan nama, nomor HP, atau motor, dan filter per status
- Tema terang dan gelap, pindahnya dengan efek tinta menyebar (idenya dari [Ink Bleed](https://github.com/codemure-official/ink-bleed-theme-changer) buatan Codemure)

## Menjalankan di Laragon

1. Taruh folder project di `C:\laragon\www`, lalu nyalakan Apache dan MySQL.
2. Buat database `sistem_booking`.
3. Jalankan di terminal Laragon:

```
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
```

4. Buka `http://iscom-reservasi-moge.test` atau jalankan `php artisan serve`.

Untuk menjalankan test: `php artisan test`

## Deploy ke Vercel

Sudah ada `vercel.json` dan `api/index.php`. Vercel tidak menyediakan MySQL, jadi
perlu database online (misalnya Aiven atau TiDB Cloud). Isi di Environment Variables
project Vercel: `APP_KEY`, `APP_URL`, `DB_CONNECTION` (isi `mysql`), `DB_HOST`, `DB_PORT`, `DB_DATABASE`,
`DB_USERNAME`, `DB_PASSWORD`. Migrasi dijalankan dari laptop dengan `.env` yang
mengarah ke database online tersebut.

## Kredit foto

Foto motor diambil dari Wikimedia Commons:

- Kawasaki Ninja ZX-6R oleh Sound Media (CC0)
- Harley-Davidson Iron 883 oleh Alek Kirstein (CC BY-SA 2.0)
- Ducati Panigale V2 oleh Terragio67 (CC BY-SA 4.0)
- Yamaha YZF-R1M oleh Dennis Bratland (CC BY-SA 4.0)
- Honda CBR1000RR-R oleh Chanokchon (CC BY-SA 4.0)
