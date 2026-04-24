<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Transaksi Peminjaman</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.5;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            border-bottom: 3px double #4338ca;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            font-weight: 800;
            color: #312e81;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 14px;
            font-weight: 700;
            color: #4338ca;
            margin-bottom: 4px;
        }
        .header p {
            font-size: 10px;
            color: #64748b;
        }

        /* === INFO BAR === */
        .info-bar {
            display: table;
            width: 100%;
            margin-bottom: 16px;
            font-size: 10px;
        }
        .info-bar .left {
            display: table-cell;
            text-align: left;
            vertical-align: middle;
        }
        .info-bar .right {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
        }
        .info-bar strong {
            color: #1e293b;
        }

        /* === SUMMARY CARDS === */
        .summary {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: collapse;
        }
        .summary td {
            width: 20%;
            padding: 10px 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .summary .label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .summary .value {
            font-size: 18px;
            font-weight: 800;
            margin-top: 2px;
        }
        .summary .total { color: #4338ca; }
        .summary .menunggu { color: #f59e0b; }
        .summary .dipinjam { color: #3b82f6; }
        .summary .dikembalikan { color: #10b981; }
        .summary .ditolak { color: #ef4444; }

        /* === TABLE === */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .data-table thead th {
            background: #4338ca;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #3730a3;
        }
        .data-table thead th.center {
            text-align: center;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .data-table tbody tr:hover {
            background: #eef2ff;
        }
        .data-table tbody td {
            padding: 7px 6px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }
        .data-table tbody td.center {
            text-align: center;
        }

        /* === STATUS BADGES === */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .badge-menunggu {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-dipinjam {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-dikembalikan {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-terlambat {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-menunggu_pengembalian {
            background: #ede9fe;
            color: #5b21b6;
        }

        /* === DENDA TEXT === */
        .denda-text {
            color: #dc2626;
            font-weight: 700;
            font-size: 9px;
        }

        /* === BOOK LIST === */
        .book-list {
            margin: 0;
            padding-left: 14px;
        }
        .book-list li {
            margin-bottom: 2px;
        }
        .book-qty {
            background: #e2e8f0;
            padding: 1px 5px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 700;
            color: #475569;
        }

        /* === FOOTER === */
        .footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            font-size: 9px;
            color: #94a3b8;
        }
        .footer-table {
            width: 100%;
        }
        .footer-table td {
            vertical-align: top;
        }
        .footer-left {
            text-align: left;
        }
        .footer-right {
            text-align: right;
        }

        /* === SIGNATURE === */
        .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 40px;
        }
        .signature .date {
            font-size: 10px;
            color: #475569;
            margin-bottom: 60px;
        }
        .signature .line {
            border-top: 1px solid #1e293b;
            display: inline-block;
            width: 180px;
            margin-bottom: 4px;
        }
        .signature .name {
            font-size: 10px;
            font-weight: 700;
            color: #1e293b;
        }
        .signature .role {
            font-size: 9px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <h1>PERPUSTAKAAN SEKOLAH</h1>
        <h2>Laporan Data Transaksi Peminjaman Buku</h2>
        <p>Sistem Informasi Manajemen Perpustakaan Digital</p>
    </div>

    <!-- INFO BAR -->
    <div class="info-bar">
        <div class="left">
            <strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }} WIB
            @if($filterStatus)
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>Filter Status:</strong> {{ ucfirst(str_replace('_', ' ', $filterStatus)) }}
            @endif
            @if($filterSearch)
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <strong>Pencarian:</strong> "{{ $filterSearch }}"
            @endif
        </div>
        <div class="right">
            <strong>Dicetak oleh:</strong> {{ auth()->user()->name }}
        </div>
    </div>

    <!-- SUMMARY -->
    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Transaksi</div>
                <div class="value total">{{ $summary['total'] }}</div>
            </td>
            <td>
                <div class="label">Menunggu</div>
                <div class="value menunggu">{{ $summary['menunggu'] }}</div>
            </td>
            <td>
                <div class="label">Dipinjam</div>
                <div class="value dipinjam">{{ $summary['dipinjam'] }}</div>
            </td>
            <td>
                <div class="label">Dikembalikan</div>
                <div class="value dikembalikan">{{ $summary['dikembalikan'] }}</div>
            </td>
            <td>
                <div class="label">Ditolak</div>
                <div class="value ditolak">{{ $summary['ditolak'] }}</div>
            </td>
        </tr>
    </table>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="center">No</th>
                <th style="width: 100px;">Peminjam</th>
                <th>Buku (Jumlah)</th>
                <th style="width: 90px;">Tgl Pinjam</th>
                <th style="width: 90px;">Tgl Kembali</th>
                <th style="width: 80px;" class="center">Status</th>
                <th style="width: 70px;" class="center">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $i => $transaction)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td><strong>{{ $transaction->user->name }}</strong></td>
                    <td>
                        <ul class="book-list">
                            @foreach($transaction->detailPeminjaman as $detail)
                                <li>{{ $detail->buku->judul }} <span class="book-qty">x{{ $detail->jumlah }}</span></li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $transaction->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>
                        @if($transaction->tanggal_kembali)
                            {{ $transaction->tanggal_kembali->format('d/m/Y') }}
                        @else
                            <span style="color: #94a3b8; font-style: italic;">-</span>
                        @endif
                    </td>
                    <td class="center">
                        @php
                            $statusClass = match($transaction->status_pinjaman) {
                                'menunggu' => 'badge-menunggu',
                                'dipinjam' => 'badge-dipinjam',
                                'dikembalikan' => 'badge-dikembalikan',
                                'ditolak' => 'badge-ditolak',
                                'terlambat' => 'badge-terlambat',
                                'menunggu_pengembalian' => 'badge-menunggu_pengembalian',
                                default => 'badge-dipinjam',
                            };
                            $statusLabel = match($transaction->status_pinjaman) {
                                'menunggu' => 'Menunggu',
                                'dipinjam' => 'Dipinjam',
                                'dikembalikan' => 'Dikembalikan',
                                'ditolak' => 'Ditolak',
                                'terlambat' => 'Terlambat',
                                'menunggu_pengembalian' => 'Mnt. Kembali',
                                default => $transaction->status_pinjaman,
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="center">
                        @if($transaction->pengembalian && $transaction->pengembalian->denda)
                            <span class="denda-text">Rp {{ number_format($transaction->pengembalian->denda->jumlah_denda, 0, ',', '.') }}</span>
                        @else
                            <span style="color: #94a3b8;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center" style="padding: 20px; color: #94a3b8;">Tidak ada data transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TOTAL DENDA -->
    @if($totalDenda > 0)
        <div style="text-align: right; margin-bottom: 10px; font-size: 11px;">
            <strong>Total Denda: </strong> 
            <span class="denda-text" style="font-size: 12px;">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
        </div>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    Dokumen ini dicetak secara otomatis oleh Sistem Perpustakaan Digital.
                </td>
                <td class="footer-right">
                    Halaman 1 &nbsp;|&nbsp; Total {{ count($transactions) }} transaksi
                </td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURE -->
    <div class="signature">
        <div class="date">{{ now()->isoFormat('D MMMM Y') }}</div>
        <div class="line"></div>
        <div class="name">{{ auth()->user()->name }}</div>
        <div class="role">Administrator Perpustakaan</div>
    </div>
</body>
</html>
