<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Paginator::useBootstrapFive(); //

        // Format harga: tampilkan "Gratis" kalau 0/kosong, selain itu "Rp 10.000".
        // Dipakai di seluruh view lewat @rupiah($harga).
        Blade::directive('rupiah', function ($expr) {
            return "<?php \$__n = (float) ($expr); echo \$__n > 0 ? 'Rp ' . number_format(\$__n, 0, ',', '.') : 'Gratis'; ?>";
        });

        // Hak akses Laporan: hanya admin & kadis provinsi. Dipakai di view lewat
        // @can('akses-laporan') untuk menyembunyikan tombol/menu Cetak Laporan.
        // (Route laporan juga dikunci middleware role:admin,kadis_provinsi.)
        Gate::define('akses-laporan', function ($user) {
            return in_array($user->role, ['admin', 'kadis_provinsi']);
        });
    }
}
