<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class SetLocaleAndPreferences
{
    public function handle(Request $request, Closure $next)
    {
        $defaults = [
            'bahasa' => 'id',
            'zona_waktu' => 'WIB',
            'per_page' => 5,
            'format_tanggal' => 'DD/MM/YYYY',
        ];

        $filePath = storage_path('app/preferensi.json');
        if (File::exists($filePath)) {
            $saved = json_decode(File::get($filePath), true);
            if (is_array($saved)) {
                $defaults = array_merge($defaults, $saved);
            }
        }

        // Terapkan bahasa aplikasi
        $locale = session('locale', $defaults['bahasa']);
        App::setLocale($locale);

        // Terapkan zona waktu
        $timezoneMap = [
            'WIB' => 'Asia/Jakarta',
            'WITA' => 'Asia/Makassar',
            'WIT' => 'Asia/Jayapura',
            'UTC' => 'UTC',
        ];
        $tz = $timezoneMap[$defaults['zona_waktu']] ?? 'Asia/Jakarta';
        config(['app.timezone' => $tz]);
        config(['app.per_page' => $defaults['per_page']]);
        config(['app.format_tanggal' => $defaults['format_tanggal']]);
        date_default_timezone_set($tz);

        // Bagikan variabel preferensi ke seluruh Blade views
        View::share('preferensi', $defaults);

        return $next($request);
    }
}
