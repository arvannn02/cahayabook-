<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriBuku;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriBuku::withCount('buku');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kategori', 'like', "%{$search}%")
                  ->orWhere('kode_kategori', 'like', "%{$search}%")
                  ->orWhere('lokasi_rak', 'like', "%{$search}%");
            });
        }

        // Filter status buku
        if ($request->filled('status')) {
            if ($request->status === 'has_book') {
                $query->has('buku');
            } elseif ($request->status === 'empty') {
                $query->doesntHave('buku');
            }
        }

        // Filter lokasi rak
        if ($request->filled('rak')) {
            $query->where('lokasi_rak', $request->rak);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'nama_asc':
                    $query->orderBy('nama_kategori', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('nama_kategori', 'desc');
                    break;
                case 'buku_desc':
                    $query->orderByDesc('buku_count');
                    break;
                case 'buku_asc':
                    $query->orderBy('buku_count', 'asc');
                    break;
                default:
                    $query->orderBy('id', 'asc');
                    break;
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        $kategoris = $query->paginate(10)->withQueryString();

        $totalKategori = KategoriBuku::count();
        $kategoriKosong = KategoriBuku::doesntHave('buku')->count();
        $kategoriPopuler = KategoriBuku::withCount('buku')->orderByDesc('buku_count')->first();

        // Calculate rack filled percentage if rak model exists
        $totalRak = \App\Models\RakBuku::count();
        $rakTerisi = \App\Models\RakBuku::whereHas('buku')->count();
        $rakTerisiPersen = $totalRak > 0 ? round(($rakTerisi / $totalRak) * 100) : 0;

        // Unique rak list for filter dropdown
        $daftarRak = KategoriBuku::whereNotNull('lokasi_rak')
            ->where('lokasi_rak', '!=', '')
            ->distinct()
            ->pluck('lokasi_rak');

        // Master rak buku for dropdown in create/edit modal
        $semuaRak = \App\Models\RakBuku::orderBy('kode_rak', 'asc')->get();

        return view('kategori.index', compact(
            'kategoris',
            'totalKategori',
            'kategoriKosong',
            'kategoriPopuler',
            'rakTerisiPersen',
            'daftarRak',
            'semuaRak'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode_kategori' => ['nullable', 'string', 'max:50'],
            'lokasi_rak'    => ['nullable', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
        ]);

        if (empty($data['kode_kategori'])) {
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $data['nama_kategori']);
            $initials = strtoupper(substr($cleanName, 0, 3));
            $data['kode_kategori'] = 'KAT - ' . ($initials ?: 'GEN');
        }

        $kategori = KategoriBuku::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan.',
                'data' => [
                    'id' => $kategori->id,
                    'text' => $kategori->nama_kategori,
                ],
            ]);
        }

        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriBuku $kategori)
    {
        $data = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100'],
            'kode_kategori' => ['nullable', 'string', 'max:50'],
            'lokasi_rak'    => ['nullable', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string'],
        ]);

        $kategori->update($data);
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(KategoriBuku $kategori)
    {
        if ($kategori->buku()->exists()) {
            return back()->with('error', 'Kategori yang masih memiliki buku tidak dapat dihapus.');
        }
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
