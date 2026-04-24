<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'peminjaman_id',
        'buku_id',
        'jumlah',
    ];

    /**
     * DetailPeminjaman belongsTo Peminjaman.
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * DetailPeminjaman belongsTo Buku.
     */
    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
