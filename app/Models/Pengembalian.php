<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_dikembalikan',
        'kondisi_buku',
        'status',
    ];

    protected $casts = [
        'tanggal_dikembalikan' => 'date',
    ];

    /**
     * Pengembalian belongsTo Peminjaman.
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    /**
     * Pengembalian hasMany DetailPengembalian.
     */
    public function detailPengembalian()
    {
        return $this->hasMany(DetailPengembalian::class, 'pengembalian_id');
    }

    /**
     * Pengembalian hasOne Denda.
     */
    public function denda()
    {
        return $this->hasOne(Denda::class, 'pengembalian_id');
    }
}
