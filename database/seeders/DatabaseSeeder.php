<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create Siswa with Anggota
        $siswa1 = User::create([
            'name' => 'Ahmad Fauzi',
            'username' => 'ahmad',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);

        Anggota::create([
            'user_id' => $siswa1->id,
            'alamat' => 'Jl. Merdeka No. 10, Jakarta',
            'no_telepon' => '081234567890',
        ]);

        $siswa2 = User::create([
            'name' => 'Siti Nurhaliza',
            'username' => 'siti',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);

        Anggota::create([
            'user_id' => $siswa2->id,
            'alamat' => 'Jl. Sudirman No. 25, Bandung',
            'no_telepon' => '089876543210',
        ]);

        // Siswa tanpa anggota (untuk test alur pendaftaran)
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa',
        ]);

        // Create Kategori
        $kategoris = [
            ['nama_kategori' => 'Novel'],
            ['nama_kategori' => 'Fiksi'],
            ['nama_kategori' => 'Non-Fiksi'],
            ['nama_kategori' => 'Sains'],
            ['nama_kategori' => 'Sejarah'],
            ['nama_kategori' => 'Self-Improvement'],
        ];

        foreach ($kategoris as $kat) {
            Kategori::create($kat);
        }

        // Create Buku
        $books = [
            ['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'stok' => 5, 'kategori_id' => 1],
            ['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'stok' => 3, 'kategori_id' => 1],
            ['judul' => 'Negeri 5 Menara', 'penulis' => 'Ahmad Fuadi', 'stok' => 4, 'kategori_id' => 1],
            ['judul' => 'Perahu Kertas', 'penulis' => 'Dee Lestari', 'stok' => 2, 'kategori_id' => 2],
            ['judul' => 'Sang Pemimpi', 'penulis' => 'Andrea Hirata', 'stok' => 6, 'kategori_id' => 1],
            ['judul' => 'Ayat-Ayat Cinta', 'penulis' => 'Habiburrahman El Shirazy', 'stok' => 3, 'kategori_id' => 2],
            ['judul' => 'Ronggeng Dukuh Paruk', 'penulis' => 'Ahmad Tohari', 'stok' => 4, 'kategori_id' => 1],
            ['judul' => 'Tenggelamnya Kapal Van Der Wijck', 'penulis' => 'Hamka', 'stok' => 2, 'kategori_id' => 5],
            ['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'stok' => 7, 'kategori_id' => 6],
            ['judul' => 'Atomic Habits', 'penulis' => 'James Clear', 'stok' => 5, 'kategori_id' => 6],
        ];

        foreach ($books as $book) {
            Buku::create($book);
        }
    }
}
