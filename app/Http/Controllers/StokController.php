<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StokBuku;
use Illuminate\Support\Facades\DB;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $query = StokBuku::with(['buku.kategori', 'buku.rak', 'buku.penulis', 'buku.penerbit']);

        // 1. Cari ID atau Judul (ISBN, Judul, atau ID)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($sq) use ($search) {
                $sq->whereHas('buku', function ($q) use ($search) {
                    $q->where('isbn', 'like', "%{$search}%")
                      ->orWhere('judul', 'like', "%{$search}%")
                      ->orWhere('id', $search);
                });
            });
        }

        // 2. Filter Kategori
        if ($request->filled('kategori')) {
            $kategori = $request->kategori;
            $query->whereHas('buku', function ($q) use ($kategori) {
                if (is_numeric($kategori)) {
                    $q->where('id_kategori', $kategori);
                } else {
                    $q->whereHas('kategori', function ($kq) use ($kategori) {
                        $kq->where('nama_kategori', $kategori);
                    });
                }
            });
        }

        // 3. Filter Status (Tersedia, Stok Rendah, Habis)
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            if ($status === 'tersedia') {
                $query->whereColumn('stok_sisa', '>', 'min_stok')
                      ->where('stok_sisa', '>', 0);
            } elseif ($status === 'stok_rendah' || $status === 'rendah' || $status === 'menipis') {
                $query->whereColumn('stok_sisa', '<=', 'min_stok')
                      ->where('stok_sisa', '>', 0);
            } elseif ($status === 'habis') {
                $query->where('stok_sisa', '<=', 0);
            }
        }

        $perPage = config('app.per_page', 5);
        $stok = $query->paginate($perPage)->withQueryString();
        $kategoris = \App\Models\KategoriBuku::orderBy('nama_kategori')->get();

        // Dynamic stats for header cards
        $totalJudulBuku = StokBuku::distinct('id_buku')->count('id_buku');
        $totalEksemplar = (int) StokBuku::sum('stok_sisa');
        $stokMenipis = StokBuku::where(function($q) {
            $q->whereColumn('stok_sisa', '<=', 'min_stok')
              ->orWhere('stok_sisa', '<=', 5);
        })->where('stok_sisa', '>', 0)->count();

        $kategoriTerbanyak = DB::table('stok_buku')
            ->join('buku', 'stok_buku.id_buku', '=', 'buku.id')
            ->join('kategori_buku', 'buku.id_kategori', '=', 'kategori_buku.id')
            ->select('kategori_buku.nama_kategori', DB::raw('SUM(stok_buku.stok_sisa) as total_eksemplar'))
            ->groupBy('kategori_buku.id', 'kategori_buku.nama_kategori')
            ->orderByDesc('total_eksemplar')
            ->value('nama_kategori') ?? 'Fiksi & Sastra';

        $activeKategori = null;
        if ($request->filled('kategori')) {
            $activeKategori = $kategoris->firstWhere('id', $request->kategori) 
                           ?? $kategoris->firstWhere('nama_kategori', $request->kategori);
        }
        $activeKategoriLabel = $activeKategori ? $activeKategori->nama_kategori : 'Semua Kategori';

        $statusLabels = [
            'tersedia'    => 'Tersedia',
            'stok_rendah' => 'Stok Rendah',
            'habis'       => 'Habis',
        ];
        $activeStatusLabel = $statusLabels[strtolower($request->status ?? '')] ?? 'Semua Status';

        return view('stok.index', compact(
            'stok',
            'kategoris',
            'totalJudulBuku',
            'totalEksemplar',
            'stokMenipis',
            'kategoriTerbanyak',
            'activeKategoriLabel',
            'activeStatusLabel'
        ));
    }

    public function update(Request $request, StokBuku $stok)
    {
        $data = $request->validate(['stok_masuk' => ['required', 'integer', 'min:0'], 'stok_keluar' => ['required', 'integer', 'min:0'], 'min_stok' => ['required', 'integer', 'min:0']]);
        $data['stok_sisa'] = $data['stok_masuk'] - $data['stok_keluar'];
        if ($data['stok_sisa'] < 0) return back()->withErrors(['stok_keluar' => 'Stok keluar tidak boleh melebihi stok masuk.']);
        $stok->update($data);
        return back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(StokBuku $stok)
    {
        $stok->delete();
        return back()->with('success', 'Data stok berhasil dihapus.');
    }
}
