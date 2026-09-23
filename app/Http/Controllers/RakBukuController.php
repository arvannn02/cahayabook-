<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RakBuku;

class RakBukuController extends Controller
{
    public function index(Request $request)
    {
        $query = RakBuku::withCount('buku');

        // 1. Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_rak', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('kategori_buku', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status Kapasitas
        if ($request->filled('status')) {
            if ($request->status === 'penuh') {
                $query->whereRaw('(select count(*) from buku where rak_buku.id = buku.id_rak) >= rak_buku.kapasitas_max');
            } elseif ($request->status === 'terisi') {
                $query->has('buku');
            } elseif ($request->status === 'tersedia') {
                $query->doesntHave('buku');
            }
        }

        // 3. Filter Kategori Buku
        if ($request->filled('kategori')) {
            $query->where('kategori_buku', $request->kategori);
        }

        // 4. Filter Lokasi / Area
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', "%{$request->lokasi}%");
        }

        // 5. Pengurutan Data (Sort)
        match ($request->sort ?? 'default') {
            'kode_asc'        => $query->orderBy('kode_rak', 'asc'),
            'kode_desc'       => $query->orderBy('kode_rak', 'desc'),
            'kapasitas_desc'  => $query->orderBy('kapasitas_max', 'desc'),
            'kapasitas_asc'   => $query->orderBy('kapasitas_max', 'asc'),
            'buku_desc'       => $query->orderByDesc('buku_count'),
            'buku_asc'        => $query->orderBy('buku_count', 'asc'),
            'terbaru'         => $query->orderBy('id', 'desc'),
            'terlama'         => $query->orderBy('id', 'asc'),
            default           => $query->orderBy('id', 'asc'),
        };

        $rak = $query->paginate(10)->withQueryString();

        // Stats (Data Riil Database)
        $allRak = RakBuku::withCount('buku')->get();
        $totalRak = $allRak->count();
        $rakPenuh = $allRak->filter(fn($r) => $r->buku_count >= $r->kapasitas_max)->count();
        $rakTerisi = $allRak->filter(fn($r) => $r->buku_count > 0)->count();
        $kapasitasTersedia = $allRak->sum(fn($r) => max(0, $r->kapasitas_max - $r->buku_count));
        $totalKapasitas = $allRak->sum('kapasitas_max');
        $persenKapasitasTersedia = $totalKapasitas > 0 ? round(($kapasitasTersedia / $totalKapasitas) * 100) : 100;

        // Master kategori dari sistem untuk dropdown & pemetaan kode otomatis
        $kategoriList = \App\Models\KategoriBuku::orderBy('nama_kategori', 'asc')->get();
        $nextKodeRakDefault = RakBuku::generateKodeRak();

        // Peta kode rak otomatis untuk tiap kategori yang ada di sistem
        $kodeRakMap = ['' => $nextKodeRakDefault];
        foreach ($kategoriList as $k) {
            $kodeRakMap[$k->nama_kategori] = RakBuku::generateKodeRak($k->nama_kategori);
        }

        // Distinct lists for filter popover (gabungan master kategori dan kategori di rak)
        $daftarKategori = $kategoriList->pluck('nama_kategori')
            ->merge(RakBuku::whereNotNull('kategori_buku')->where('kategori_buku', '!=', '')->pluck('kategori_buku'))
            ->unique()
            ->values();

        $daftarLokasi = RakBuku::whereNotNull('lokasi')
            ->where('lokasi', '!=', '')
            ->distinct()
            ->orderBy('lokasi')
            ->pluck('lokasi');

        return view('rak.index', compact(
            'rak',
            'totalRak',
            'rakPenuh',
            'rakTerisi',
            'kapasitasTersedia',
            'persenKapasitasTersedia',
            'daftarKategori',
            'daftarLokasi',
            'kategoriList',
            'nextKodeRakDefault',
            'kodeRakMap'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_rak' => ['nullable', 'string', 'max:50'],
            'lokasi' => ['required', 'string', 'max:150'],
            'kategori_buku' => ['nullable', 'string', 'max:100'],
            'kapasitas_max' => ['required', 'integer', 'min:1']
        ]);

        // Generate kode rak otomatis jika kosong atau jika kode sudah terpakai
        if (empty($data['kode_rak']) || RakBuku::where('kode_rak', $data['kode_rak'])->exists()) {
            $data['kode_rak'] = RakBuku::generateKodeRak($data['kategori_buku'] ?? null);
        }

        $rak = RakBuku::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rak dengan kode ' . $data['kode_rak'] . ' berhasil ditambahkan.',
                'data' => [
                    'id' => $rak->id,
                    'text' => $rak->kode_rak . ' (' . $rak->lokasi . ')',
                ],
            ]);
        }

        return back()->with('success', 'Rak dengan kode ' . $data['kode_rak'] . ' berhasil ditambahkan.');
    }

    public function update(Request $request, RakBuku $rak)
    {
        $data = $request->validate([
            'kode_rak' => ['required', 'string', 'max:50', 'unique:rak_buku,kode_rak,' . $rak->id],
            'lokasi' => ['required', 'string', 'max:150'],
            'kategori_buku' => ['nullable', 'string', 'max:100'],
            'kapasitas_max' => ['required', 'integer', 'min:1']
        ]);
        $rak->update($data);
        return back()->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(RakBuku $rak)
    {
        if ($rak->buku()->exists()) {
            return back()->with('error', 'Rak yang masih digunakan buku tidak dapat dihapus.');
        }
        $rak->delete();
        return back()->with('success', 'Rak berhasil dihapus.');
    }
}
