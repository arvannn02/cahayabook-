<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbit;
use App\Models\Buku;
use Carbon\Carbon;

class PenerbitController extends Controller
{
    public function index(Request $request)
    {
        $query = Penerbit::withCount('buku');

        // 1. Filter Pencarian Global
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_penerbit', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status Kemitraan (Ada Buku vs Belum Ada Buku)
        if ($request->filled('status')) {
            if ($request->status === 'has_book') {
                $query->has('buku');
            } elseif ($request->status === 'empty') {
                $query->doesntHave('buku');
            }
        }

        // 3. Filter Kota / Wilayah Alamat
        if ($request->filled('kota')) {
            $query->where('alamat', 'like', "%{$request->kota}%");
        }

        // 4. Pengurutan Data (Urutkan)
        match ($request->sort ?? 'default') {
            'nama_asc'   => $query->orderBy('nama_penerbit', 'asc'),
            'nama_desc'  => $query->orderBy('nama_penerbit', 'desc'),
            'buku_desc'  => $query->orderByDesc('buku_count'),
            'buku_asc'   => $query->orderBy('buku_count', 'asc'),
            'terbaru'    => $query->orderByDesc('id'),
            'terlama'    => $query->orderBy('id', 'asc'),
            default      => $query->orderBy('id', 'asc'),
        };

        $penerbit = $query->paginate(10)->withQueryString();

        // 5. Statistik Riil dari Database
        $totalPenerbit     = Penerbit::count();
        $penerbitAktif     = Penerbit::has('buku')->count();
        $penerbitKosong    = Penerbit::doesntHave('buku')->count();
        $totalBukuPenerbit = Buku::count();
        $persenAktif       = $totalPenerbit > 0 ? round(($penerbitAktif / $totalPenerbit) * 100) : 0;
        $kerjasamaBaru     = Penerbit::where('created_at', '>=', Carbon::now()->startOfMonth())->count();

        // 6. Daftar Kota / Wilayah Unik untuk Filter
        $daftarKota = Penerbit::whereNotNull('alamat')
            ->where('alamat', '!=', '')
            ->pluck('alamat')
            ->map(function ($alamat) {
                $parts = explode(',', $alamat);
                return trim(end($parts));
            })
            ->unique()
            ->filter()
            ->values();

        return view('penerbit.index', compact(
            'penerbit',
            'totalPenerbit',
            'penerbitAktif',
            'penerbitKosong',
            'totalBukuPenerbit',
            'persenAktif',
            'kerjasamaBaru',
            'daftarKota'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_penerbit' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:15']
        ], [
            'nama_penerbit.required' => 'Nama penerbit wajib diisi.',
            'telepon.max' => 'Nomor telepon maksimal 15 digit.',
        ]);
        Penerbit::create($data);
        return back()->with('success', 'Penerbit berhasil ditambahkan.');
    }

    public function update(Request $request, Penerbit $penerbit)
    {
        $data = $request->validate([
            'nama_penerbit' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:15']
        ], [
            'nama_penerbit.required' => 'Nama penerbit wajib diisi.',
            'telepon.max' => 'Nomor telepon maksimal 15 digit.',
        ]);
        $penerbit->update($data);
        return back()->with('success', 'Penerbit berhasil diperbarui.');
    }

    public function destroy(Penerbit $penerbit)
    {
        if ($penerbit->buku()->exists()) {
            return back()->with('error', 'Penerbit yang masih memiliki buku tidak dapat dihapus.');
        }
        $penerbit->delete();
        return back()->with('success', 'Penerbit berhasil dihapus.');
    }
}
