<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Kategori hasMany Buku.
     */
    public function buku()
    {
        return $this->hasMany(Buku::class, 'kategori_id', 'id_kategori');
    }
}
