<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengembalian extends Model
{
    protected $table = 'detail_pengembalian';

    protected $fillable = [
        'pengembalian_id',
        'jumlah',
        'keterangan',
    ];

    /**
     * DetailPengembalian belongsTo Pengembalian.
     */
    public function pengembalian()
    {
        return $this->belongsTo(Pengembalian::class, 'pengembalian_id');
    }
}
