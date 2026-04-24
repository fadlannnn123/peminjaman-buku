<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update peminjaman status
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status_pinjaman ENUM('menunggu', 'dipinjam', 'menunggu_pengembalian', 'dikembalikan', 'terlambat', 'ditolak') NOT NULL DEFAULT 'menunggu'");

        // Update denda status safely
        DB::statement("ALTER TABLE denda MODIFY COLUMN status_bayar VARCHAR(20) NOT NULL DEFAULT 'belum_bayar'");
        DB::table('denda')->where('status_bayar', 'lunas')->update(['status_bayar' => 'dibayar']);
        DB::statement("ALTER TABLE denda MODIFY COLUMN status_bayar ENUM('belum_bayar', 'dibayar') NOT NULL DEFAULT 'belum_bayar'");
    }

    public function down(): void
    {
        // Revert denda status safely
        DB::statement("ALTER TABLE denda MODIFY COLUMN status_bayar VARCHAR(20) NOT NULL DEFAULT 'belum_bayar'");
        DB::table('denda')->where('status_bayar', 'dibayar')->update(['status_bayar' => 'lunas']);
        DB::statement("ALTER TABLE denda MODIFY COLUMN status_bayar ENUM('belum_bayar', 'lunas') NOT NULL DEFAULT 'belum_bayar'");

        // Revert peminjaman status
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status_pinjaman ENUM('dipinjam', 'dikembalikan', 'terlambat') NOT NULL DEFAULT 'dipinjam'");
    }
};
