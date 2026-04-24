<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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
        // Route model bindings for custom table/PK models
        Route::model('book', \App\Models\Buku::class);
        Route::model('member', \App\Models\Anggota::class);
        Route::model('transaction', \App\Models\Peminjaman::class);
        Route::bind('kategori', function ($value) {
            return \App\Models\Kategori::where('id_kategori', $value)->firstOrFail();
        });
        Route::model('denda', \App\Models\Denda::class);
    }
}
