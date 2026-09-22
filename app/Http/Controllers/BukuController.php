<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\Penerbit;
use Illuminate\Http\Request;
use App\Models\Penulis;
use App\Models\RakBuku;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with(['kategori', 'penulis', 'penerbit', 'rak', 'stok'])
            ->select('buku.*');

        // Filter pencarian global (judul, isbn, penulis, penerbit)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('buku.judul', 'like', "%{$search}%")
                  ->orWhere('buku.isbn', 'like', "%{$search}%")
                  ->orWhereHas('penulis', fn($pq) => $pq->where('nama_penulis', 'like', "%{$search}%"))
                  ->orWhereHas('penerbit', fn($pubq) => $pubq->where('nama_penerbit', 'like', "%{$search}%"));
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('buku.id_kategori', $request->kategori);
        }

        // Filter status stok (low stock = stok sisa <= min_stok)
        if ($request->filled('status')) {
            if ($request->status === 'low') {
                $query->whereHas('stok', fn($q) =>
                    $q->whereColumn('stok_buku.stok_sisa', '<=', 'stok_buku.min_stok')
                );
            } elseif ($request->status === 'available') {
                $query->whereHas('stok', fn($q) =>
                    $q->whereColumn('stok_buku.stok_sisa', '>', 'stok_buku.min_stok')
                );
            }
        }

        // Sort
        match($request->sort ?? 'terbaru') {
            'terlama'    => $query->orderBy('buku.id', 'asc'),
            'judul_az'   => $query->orderBy('buku.judul', 'asc'),
            'judul_za'   => $query->orderBy('buku.judul', 'desc'),
            'stok_asc'   => $query->leftJoin('stok_buku', 'buku.id', '=', 'stok_buku.id_buku')->orderBy('stok_buku.stok_sisa', 'asc'),
            'stok_desc'  => $query->leftJoin('stok_buku', 'buku.id', '=', 'stok_buku.id_buku')->orderBy('stok_buku.stok_sisa', 'desc'),
            default      => $query->orderBy('buku.id', 'desc'),
        };

        $perPage = config('app.per_page', 5);
        $buku = $query->paginate($perPage)->withQueryString();
        $kategoris    = KategoriBuku::all();
        $penulis      = Penulis::all();
        $penerbit     = Penerbit::all();
        $rak          = RakBuku::all();
        $totalBuku    = Buku::count();
        $totalKategori = KategoriBuku::count();
        $totalPenerbit = Penerbit::count();
        $totalStokFisik = (int) \App\Models\StokBuku::sum('stok_sisa');

        return view('buku.index', compact(
            'buku', 'kategoris', 'penulis', 'penerbit', 'rak', 'totalBuku', 'totalKategori', 'totalPenerbit', 'totalStokFisik'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'isbn' => ['required', 'string', 'max:30', 'unique:buku,isbn'], 'judul' => ['required', 'string', 'max:200'],
            'id_kategori' => ['required', 'exists:kategori_buku,id'], 'id_penulis' => ['required', 'exists:penulis,id'],
            'id_penerbit' => ['required', 'exists:penerbit,id'], 'id_rak' => ['required', 'exists:rak_buku,id'],
            'harga_beli' => ['nullable', 'numeric', 'min:0'], 'harga_jual' => ['nullable', 'numeric', 'min:0'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
        ]);
        Buku::create($data);
        return back()->with('success', 'Buku berhasil ditambahkan.');
    }

    public function update(Request $request, Buku $buku)
    {
        $data = $request->validate([
            'isbn' => ['required', 'string', 'max:30', 'unique:buku,isbn,'.$buku->id], 'judul' => ['required', 'string', 'max:200'],
            'id_kategori' => ['required', 'exists:kategori_buku,id'], 'id_penulis' => ['required', 'exists:penulis,id'],
            'id_penerbit' => ['required', 'exists:penerbit,id'], 'id_rak' => ['required', 'exists:rak_buku,id'],
            'harga_beli' => ['nullable', 'numeric', 'min:0'], 'harga_jual' => ['nullable', 'numeric', 'min:0'],
            'tahun_terbit' => ['nullable', 'integer', 'min:1000', 'max:'.date('Y')],
        ]);
        $buku->update($data);
        return back()->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->stok()->exists() || $buku->hasMany(\App\Models\DetailBukuMasuk::class, 'id_buku')->exists() || $buku->hasMany(\App\Models\DetailBukuKeluar::class, 'id_buku')->exists()) {
            return back()->with('error', 'Buku yang sudah memiliki stok atau transaksi tidak dapat dihapus.');
        }
        if ($buku->sampul && file_exists(public_path('storage/' . $buku->sampul))) {
            unlink(public_path('storage/' . $buku->sampul));
        }
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function quickSearch(Request $request)
    {
        $q = trim($request->query('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $books = Buku::with(['penulis', 'kategori', 'stok'])
            ->where(function($query) use ($q) {
                $query->where('buku.judul', 'like', "%{$q}%")
                      ->orWhere('buku.isbn', 'like', "%{$q}%")
                      ->orWhereHas('penulis', fn($pq) => $pq->where('nama_penulis', 'like', "%{$q}%"))
                      ->orWhereHas('penerbit', fn($pubq) => $pubq->where('nama_penerbit', 'like', "%{$q}%"));
            })
            ->take(6)
            ->get()
            ->map(function($b) {
                return [
                    'id' => $b->id,
                    'judul' => $b->judul,
                    'isbn' => $b->isbn,
                    'penulis' => $b->penulis?->nama_penulis ?? '-',
                    'kategori' => $b->kategori?->nama_kategori ?? '-',
                    'stok' => $b->stok?->stok_sisa ?? 0,
                    'url' => route('buku.index', ['search' => $b->isbn]),
                ];
            });

        return response()->json($books);
    }
}
