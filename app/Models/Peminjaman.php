<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status_pinjaman',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    /**
     * Peminjaman belongsTo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Peminjaman hasMany DetailPeminjaman.
     */
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    /**
     * Peminjaman hasOne Pengembalian.
     */
    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    /**
     * Scope: Pending peminjaman (menunggu approval).
     */
    public function scopePending($query)
    {
        return $query->where('status_pinjaman', 'menunggu');
    }

    /**
     * Scope: Pending pengembalian (menunggu konfirmasi admin).
     */
    public function scopePendingReturn($query)
    {
        return $query->where('status_pinjaman', 'menunggu_pengembalian');
    }

    /**
     * Check if peminjaman is late.
     */
    public function isLate(): bool
    {
        if (in_array($this->status_pinjaman, ['dikembalikan', 'menunggu', 'ditolak'])) {
            return false;
        }
        $batasKembali = $this->tanggal_kembali ? $this->tanggal_kembali->copy() : $this->tanggal_pinjam->copy()->addDays(7);
        return now()->diffInDays($batasKembali, false) < 0;
    }

    /**
     * Calculate late days.
     */
    public function lateDays(): int
    {
        $batasKembali = $this->tanggal_kembali ? $this->tanggal_kembali->copy() : $this->tanggal_pinjam->copy()->addDays(7);
        $tanggalAktual = $this->pengembalian ? $this->pengembalian->tanggal_dikembalikan : now();
        $late = $batasKembali->diffInDays($tanggalAktual, false);
        return max(0, $late);
    }
}
