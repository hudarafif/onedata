<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Pagination\Paginator;
use App\Models\Kandidat;
use App\Models\Posisi;
use App\Models\InterviewHr;
use App\Observers\KandidatObserver;
use App\Observers\PosisiObserver;
use App\Policies\TempaPesertaPolicy;
use App\Policies\TempaPolicy;
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
        // Register middleware alias for role checks
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', \App\Http\Middleware\EnsureRole::class);
        Paginator::useTailwind();
        \Carbon\Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        Kandidat::observe(KandidatObserver::class);
        Posisi::observe(PosisiObserver::class);
        Paginator::useTailwind();

        // Register Gates for TEMPA
        Gate::define('viewTempaPeserta', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('createTempaPeserta', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('editTempaPeserta', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('deleteTempaPeserta', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });

        Gate::policy(\App\Models\TempaPeserta::class, TempaPesertaPolicy::class);

        Gate::define('viewTempaAbsensi', [TempaPolicy::class, 'viewTempaAbsensi']);
        Gate::define('createTempaAbsensi', [TempaPolicy::class, 'createTempaAbsensi']);
        Gate::define('editTempaAbsensi', [TempaPolicy::class, 'updateTempaAbsensi']);
        Gate::define('deleteTempaAbsensi', [TempaPolicy::class, 'deleteTempaAbsensi']);

        Gate::define('viewTempaMonitoring', [TempaPolicy::class, 'viewTempaMonitoring']);
        Gate::define('createTempaMateri', [TempaPolicy::class, 'createTempaMateri']);
        Gate::define('viewTempaMateri', [TempaPolicy::class, 'viewTempaMateri']);

        // Gates for TEMPA KELOMPOK
        Gate::define('viewTempaKelompok', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('createTempaKelompok', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('editTempaKelompok', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::define('deleteTempaKelompok', function($user) {
            return $user->hasRole(['admin', 'superadmin', 'ketua_tempa']);
        });
        Gate::policy(\App\Models\TempaKelompok::class, \App\Policies\TempaKelompokPolicy::class);

        // --- VIEW COMPOSER FOR DYNAMIC HEADER TITLE ---
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (!$view->offsetExists('title')) {
                $path = request()->path();
                $title = 'Dashboard'; // Default

                // Mapping URL ke Judul (Case Insensitive)
                $mappings = [
                    'users' => 'Manajemen User',
                    'karyawan' => 'Data Karyawan',
                    'rekrutmen/fpk' => 'Pengajuan FPK',
                    'rekrutmen/fpk/history' => 'History FPK',
                    'rekrutmen/posisi-manage' => 'Manage Posisi',
                    'rekrutmen/kandidat' => 'Manage Kandidat',
                    'rekrutmen' => 'Dashboard Rekrutmen',
                    'training' => 'Training',
                    'onboarding' => 'Onboarding Karyawan',
                    'turnover' => 'Data Turnover',
                    'kpi/dashboard' => 'KPI Karyawan',
                    'kbi/dashboard' => 'KBI Karyawan',
                    'kbi/monitoring' => 'Monitoring KBI',
                    'performance/rekap' => 'Rekap Performance',
                    'kompetensi/monitoring' => 'Monitoring Kompetensi',
                    'organization/holding' => 'Struktur Holding',
                    'organization/company' => 'Struktur Perusahaan',
                    'organization/subsidiary' => 'Anak Perusahaan',
                    'organization/division' => 'Struktur Divisi',
                    'organization/department' => 'Struktur Departemen',
                    'organization/unit' => 'Struktur Unit',
                    'organization/level' => 'Level Jabatan',
                    'tempa/kelompok' => 'Kelompok TEMPA',
                    'tempa/peserta' => 'Peserta TEMPA',
                    'tempa/absensi' => 'Absensi TEMPA',
                    'tempa/monitoring' => 'Monitoring TEMPA',
                    'tempa/materi' => 'Materi TEMPA',
                    'materi' => 'Materi',
                ];

                foreach ($mappings as $route => $name) {
                    if (request()->is($route) || request()->is($route . '/*')) {
                        $title = $name;
                        break;
                    }
                }

                $view->with('title', $title);
            }
        });
    }
}
