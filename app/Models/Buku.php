<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'penulis',
        'stok',
        'kategori_id',
        'foto',
    ];

    /**
     * Buku belongsTo Kategori.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    /**
     * Buku hasMany DetailPeminjaman.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'buku_id');
    }

    /**
     * Buku hasMany DetailPengembalian (through pengembalian).
     */
    public function detailPengembalian()
    {
        return $this->hasMany(DetailPengembalian::class);
    }
}
