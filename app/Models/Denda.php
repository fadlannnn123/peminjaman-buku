<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $table = 'denda';

    protected $fillable = [
        'pengembalian_id',
        'jumlah_denda',
        'status_bayar',
    ];

    /**
     * Denda belongsTo Pengembalian.
     */
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }

    /**
     * Denda per hari.
     */
    public const DENDA_PER_HARI = 5000;
}
