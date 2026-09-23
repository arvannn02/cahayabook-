<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Inventaris - Cahaya Buku</title>
    <style>
        @page {
            margin: 28px 32px 36px 32px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.35;
        }
        /* Kop Surat */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #2d4636;
            letter-spacing: -0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .brand-subtitle {
            font-size: 10px;
            color: #4b5563;
            margin: 2px 0 0 0;
        }
        .brand-address {
            font-size: 8.5px;
            color: #6b7280;
            margin: 2px 0 0 0;
        }
        .kop-divider {
            border-top: 2.5px solid #2d4636;
            border-bottom: 0.8px solid #2d4636;
            height: 3px;
            margin-bottom: 14px;
        }

        /* Dokumen Title & Metadata */
        .doc-title-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 4px 0;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            background-color: #f8faf9;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .meta-table td {
            padding: 5px 8px;
            font-size: 9px;
        }
        .meta-label {
            color: #64748b;
            font-weight: normal;
            width: 18%;
        }
        .meta-value {
            color: #0f172a;
            font-weight: bold;
            width: 32%;
        }

        /* Summary KPI Cards */
        .section-heading {
            font-size: 11px;
            font-weight: bold;
            color: #2d4636;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 14px 0 6px 0;
            padding-bottom: 3px;
            border-bottom: 1px solid #e2e8f0;
        }
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .kpi-table td {
            width: 25%;
            padding: 6px 8px;
            background-color: #fdfdfd;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .kpi-label {
            font-size: 8px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .kpi-main-val {
            font-size: 13px;
            font-weight: bold;
            color: #111827;
        }
        .kpi-sub-val {
            font-size: 8px;
            color: #4b5563;
            margin-top: 2px;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            page-break-inside: auto;
        }
        .data-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .data-table th {
            background-color: #2d4636;
            color: #ffffff;
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px 5px;
            border: 1px solid #2d4636;
            text-align: left;
            letter-spacing: 0.2px;
        }
        .data-table td {
            padding: 5px;
            font-size: 8.5px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #f8faf9;
        }
        .badge-in {
            display: inline-block;
            padding: 1.5px 5px;
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5px solid #86efac;
            font-weight: bold;
            font-size: 8px;
            border-radius: 2px;
            text-align: center;
        }
        .badge-out {
            display: inline-block;
            padding: 1.5px 5px;
            background-color: #fee2e2;
            color: #b91c1c;
            border: 0.5px solid #fca5a5;
            font-weight: bold;
            font-size: 8px;
            border-radius: 2px;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .table-total-row td {
            background-color: #edf3ee !important;
            font-weight: bold;
            color: #1b3824;
            border-top: 1.5px solid #2d4636;
            border-bottom: 1.5px solid #2d4636;
        }

        /* Signatures */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            page-break-inside: avoid;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .signature-space {
            height: 52px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 9.5px;
            color: #111827;
        }
        .signature-role {
            font-size: 8.5px;
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI --}}
    <table class="kop-table">
        <tr>
            <td style="width: 70%;">
                <div class="brand-title">Cahaya Buku</div>
                <div class="brand-subtitle">Sistem Informasi Pergudangan & Manajemen Inventaris Buku Terpadu</div>
                <div class="brand-address">Jl. Percetakan Niaga No. 12, Gudang Distribusi Utama | Telp: (021) 884-2190 | Email: inventori@cahayabook.com</div>
            </td>
            <td style="width: 30%; text-align: right; vertical-align: top;">
                <div style="font-size: 8.5px; color: #64748b;">DOKUMEN RESMI INVENTARIS</div>
                <div style="font-size: 11px; font-weight: bold; color: #2d4636; font-family: monospace;">#LAP-{{ date('YmdHis') }}</div>
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    {{-- JUDUL LAPORAN & METADATA FILTER --}}
    <div class="doc-title-container">
        <h2 class="doc-title">Laporan Rekapitulasi Inventaris & Mutasi Buku</h2>
        <div style="font-size: 9px; color: #64748b;">Rincian data pergerakan stok barang masuk, barang keluar, dan status inventaris fisik</div>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Periode Laporan</td>
            <td class="meta-value">: {{ $periodeLabel }}</td>
            <td class="meta-label">Tanggal Cetak</td>
            <td class="meta-value">: {{ $tanggalCetak }}</td>
        </tr>
        <tr>
            <td class="meta-label">Filter Kategori</td>
            <td class="meta-value">: {{ $namaKategoriTerpilih }}</td>
            <td class="meta-label">Dicetak Oleh</td>
            <td class="meta-value">: {{ $petugasCetak }} ({{ $roleCetak }})</td>
        </tr>
    </table>

    {{-- RINGKASAN METRIK UTAMA (KPI) --}}
    <div class="section-heading">I. Ringkasan Eksekutif Mutasi & Stok</div>
    <table class="kpi-table">
        <tr>
            <td>
                <div class="kpi-label">Total Transaksi</div>
                <div class="kpi-main-val">{{ number_format($totalTransaksi) }} Trx</div>
                <div class="kpi-sub-val">{{ $totalMasukTrx }} Masuk &bull; {{ $totalKeluarTrx }} Keluar</div>
            </td>
            <td>
                <div class="kpi-label">Volume Mutasi (Unit)</div>
                <div class="kpi-main-val">{{ $netMovement >= 0 ? '+' : '' }}{{ number_format($netMovement) }} <span style="font-size: 9px; font-weight: normal;">net</span></div>
                <div class="kpi-sub-val">+{{ number_format($totalUnitMasuk) }} In &bull; -{{ number_format($totalUnitKeluar) }} Out</div>
            </td>
            <td>
                <div class="kpi-label">Nilai Mutasi Finansial</div>
                <div class="kpi-main-val" style="font-size: 11px;">Rp {{ number_format($totalNilaiMasuk, 0, ',', '.') }}</div>
                <div class="kpi-sub-val">Keluar: Rp {{ number_format($totalNilaiKeluar, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="kpi-label">Kondisi Stok Fisik</div>
                <div class="kpi-main-val">{{ number_format($totalStokFisik) }} <span style="font-size: 9px; font-weight: normal;">buku</span></div>
                <div class="kpi-sub-val">Kapasitas Rak: {{ number_format($totalKapasitasRak) }} ({{ $efisiensiRak }}%)</div>
            </td>
        </tr>
    </table>

    {{-- TABEL RINCIAN TRANSAKSI DETAIL --}}
    <div class="section-heading">II. Rincian Detail Transaksi Mutasi Buku (Masuk & Keluar)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 11%;">No. Transaksi</th>
                <th style="width: 8%;" class="text-center">Tipe</th>
                <th style="width: 25%;">Judul Buku & ISBN</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 6%;" class="text-right">Qty</th>
                <th style="width: 10%;" class="text-right">Harga (Rp)</th>
                <th style="width: 11%;" class="text-right">Subtotal (Rp)</th>
                <th style="width: 13%;">Keterangan / Tujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($semuaTransaksiDetail as $idx => $trx)
            @php
                $isMasuk = $trx->tipe === 'masuk';
                $tglFormatted = \Carbon\Carbon::parse($trx->tanggal)->translatedFormat('d/m/Y');
            @endphp
            <tr>
                <td class="text-center" style="color: #64748b;">{{ $idx + 1 }}</td>
                <td>{{ $tglFormatted }}</td>
                <td style="font-family: monospace; font-weight: bold; color: #1e293b;">{{ $trx->no_transaksi }}</td>
                <td class="text-center">
                    @if($isMasuk)
                        <span class="badge-in">MASUK</span>
                    @else
                        <span class="badge-out">KELUAR</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: bold; color: #0f172a;">{{ $trx->judul }}</div>
                    <div style="font-size: 8px; color: #64748b; font-family: monospace;">ISBN: {{ $trx->isbn ?? '-' }}</div>
                </td>
                <td style="color: #334155;">{{ $trx->nama_kategori ?? '-' }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($trx->jumlah) }}</td>
                <td class="text-right">{{ number_format($trx->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($trx->subtotal, 0, ',', '.') }}</td>
                <td style="font-size: 8px; color: #475569;">
                    {{ $trx->info_tambahan ?? '-' }}
                    @if(!empty($trx->user_name))
                        <div style="color: #94a3b8; font-size: 7.5px;">User: {{ $trx->user_name }}</div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center" style="padding: 16px; color: #94a3b8; font-style: italic;">
                    Tidak ada catatan transaksi mutasi buku pada periode filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if(count($semuaTransaksiDetail) > 0)
        <tfoot>
            <tr class="table-total-row">
                <td colspan="6" class="text-right">TOTAL KESELURUHAN (MASUK & KELUAR):</td>
                <td class="text-right">{{ number_format($semuaTransaksiDetail->sum('jumlah')) }}</td>
                <td></td>
                <td class="text-right">Rp {{ number_format($semuaTransaksiDetail->sum('subtotal'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    {{-- REKAPITULASI STOK PER KATEGORI BUKU --}}
    @if(isset($kategoriDistribution) && count($kategoriDistribution) > 0)
    <div class="section-heading" style="margin-top: 14px;">III. Rekapitulasi Status Stok Fisik per Kategori</div>
    <table class="data-table" style="width: 75%;">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">No</th>
                <th style="width: 45%;">Nama Kategori Buku</th>
                <th style="width: 24%;" class="text-right">Jumlah Judul Buku</th>
                <th style="width: 25%;" class="text-right">Total Stok Fisik (Unit)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalJudulAll = 0; $totalStokAll = 0; @endphp
            @foreach($kategoriDistribution as $kIdx => $kat)
            @php
                $totalJudulAll += $kat->total_judul;
                $totalStokAll += $kat->total_stok;
            @endphp
            <tr>
                <td class="text-center" style="color: #64748b;">{{ $kIdx + 1 }}</td>
                <td style="font-weight: bold; color: #1e293b;">{{ $kat->nama_kategori }}</td>
                <td class="text-right">{{ number_format($kat->total_judul) }} judul</td>
                <td class="text-right" style="font-weight: bold; color: #2d4636;">{{ number_format($kat->total_stok) }} unit</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-total-row">
                <td colspan="2" class="text-right">TOTAL INVENTARIS:</td>
                <td class="text-right">{{ number_format($totalJudulAll) }} judul</td>
                <td class="text-right">{{ number_format($totalStokAll) }} unit</td>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- TANDA TANGAN / PENGESAHAN DOKUMEN --}}
    <table class="signature-table">
        <tr>
            <td>
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">Petugas Operasional Gudang</div>
                <div class="signature-space"></div>
                <div class="signature-name">( ............................................ )</div>
                <div class="signature-role">Staff Logistik & Inventori</div>
            </td>
            <td>
                <div>Disahkan pada: {{ date('d F Y') }}</div>
                <div style="font-weight: bold;">Kepala / Administrator Sistem</div>
                <div class="signature-space"></div>
                <div class="signature-name">{{ $petugasCetak }}</div>
                <div class="signature-role">{{ $roleCetak }} Cahaya Buku</div>
            </td>
        </tr>
    </table>

    {{-- SCRIPT NOMOR HALAMAN DOMPDF --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Dokumen ini dicetak otomatis dari Sistem Cahaya Buku | Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $size = 8;
            $font = $fontMetrics->getFont("Helvetica");
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.45, 0.45, 0.45));
        }
    </script>

</body>
</html>
