<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penulis;

class PenulisController extends Controller
{
    public function index(Request $request)
    {
        $query = Penulis::withCount('buku');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_penulis', 'like', "%{$search}%")
                  ->orWhere('bio', 'like', "%{$search}%")
                  ->orWhere('kewarganegaraan', 'like', "%{$search}%");
            });
        }

        // Filter status koleksi buku
        if ($request->filled('status')) {
            if ($request->status === 'has_book') {
                $query->has('buku');
            } elseif ($request->status === 'empty') {
                $query->doesntHave('buku');
            }
        }

        // Filter kewarganegaraan
        if ($request->filled('negara')) {
            $query->where('kewarganegaraan', $request->negara);
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'nama_asc':
                    $query->orderBy('nama_penulis', 'asc');
                    break;
                case 'nama_desc':
                    $query->orderBy('nama_penulis', 'desc');
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

        $penulis = $query->paginate(10)->withQueryString();

        $totalPenulis = Penulis::count();
        $penulisProduktif = Penulis::withCount('buku')->orderByDesc('buku_count')->first();
        $penulisBaru = Penulis::whereMonth('created_at', now()->month)->count();
        $totalBuku = \App\Models\Buku::count();
        $avgBuku = $totalPenulis > 0 ? round($totalBuku / $totalPenulis, 1) : 0;

        // Unique countries for filter dropdown
        $daftarNegara = Penulis::whereNotNull('kewarganegaraan')
            ->where('kewarganegaraan', '!=', '')
            ->distinct()
            ->pluck('kewarganegaraan');

        $negaraList = self::getCountryList();

        return view('penulis.index', compact(
            'penulis',
            'totalPenulis',
            'penulisProduktif',
            'penulisBaru',
            'avgBuku',
            'daftarNegara',
            'negaraList'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_penulis'    => ['required', 'string', 'max:150'],
            'bio'             => ['nullable', 'string'],
            'kewarganegaraan' => ['nullable', 'string', 'max:100'],
        ]);

        $penulis = Penulis::create($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penulis berhasil ditambahkan.',
                'data' => [
                    'id' => $penulis->id,
                    'text' => $penulis->nama_penulis,
                ],
            ]);
        }

        return back()->with('success', 'Penulis berhasil ditambahkan.');
    }

    public function update(Request $request, Penulis $penuli)
    {
        $data = $request->validate([
            'nama_penulis'    => ['required', 'string', 'max:150'],
            'bio'             => ['nullable', 'string'],
            'kewarganegaraan' => ['nullable', 'string', 'max:100'],
        ]);

        $penuli->update($data);
        return back()->with('success', 'Penulis berhasil diperbarui.');
    }

    public function destroy(Penulis $penuli)
    {
        if ($penuli->buku()->exists()) {
            return back()->with('error', 'Penulis yang masih memiliki buku tidak dapat dihapus.');
        }
        $penuli->delete();
        return back()->with('success', 'Penulis berhasil dihapus.');
    }

    public static function getCountryList(): array
    {
        $jsonPath = public_path('js/countries195.json');
        if (file_exists($jsonPath)) {
            $data = json_decode(file_get_contents($jsonPath), true);
            if (is_array($data) && count($data) >= 195) {
                return array_map(function ($c) {
                    $lower = strtolower($c['code']);
                    $c['flag_url'] = "https://flagcdn.com/w40/{$lower}.png";
                    return $c;
                }, $data);
            }
        }

        $countries = [
            ['code' => 'ID', 'name' => 'Indonesia', 'dial' => '+62'],
            ['code' => 'US', 'name' => 'Amerika Serikat', 'dial' => '+1'],
            ['code' => 'GB', 'name' => 'Inggris', 'dial' => '+44'],
            ['code' => 'MY', 'name' => 'Malaysia', 'dial' => '+60'],
            ['code' => 'SG', 'name' => 'Singapura', 'dial' => '+65'],
            ['code' => 'JP', 'name' => 'Jepang', 'dial' => '+81'],
        ];

        return array_map(function ($c) {
            $lower = strtolower($c['code']);
            $c['flag_url'] = "https://flagcdn.com/w40/{$lower}.png";
            return $c;
        }, $countries);
    }

    public static function findCountry(?string $query): ?array
    {
        if (empty($query)) {
            return null;
        }

        $list = self::getCountryList();
        $clean = trim($query);

        // 1. Exact match by name
        foreach ($list as $c) {
            if (strcasecmp($c['name'], $clean) === 0) {
                return $c;
            }
        }

        // 2. Exact match by code
        foreach ($list as $c) {
            if (strcasecmp($c['code'], $clean) === 0) {
                return $c;
            }
        }

        // 3. Partial / contains match (handles cases like 'Inggris (UK)' or legacy strings)
        foreach ($list as $c) {
            if (stripos($clean, $c['name']) !== false || stripos($c['name'], $clean) !== false) {
                return $c;
            }
        }

        return null;
    }
}
