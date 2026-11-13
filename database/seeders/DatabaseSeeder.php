<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Tidak ada halaman daftar akun, jadi akun admin dibuat dari sini.
    public function run(): void
    {
        // shortcut: akun testing agus / 123, isi ADMIN_PASSWORD sebelum website dipakai sungguhan
        $username = env('ADMIN_USERNAME', 'agus');
        $password = env('ADMIN_PASSWORD', '123');

        User::updateOrCreate(
            ['email' => $username],
            ['name' => 'Admin', 'password' => $password],
        );

        $this->command->info('Akun admin: '.$username.' / '.$password);
    }
}
