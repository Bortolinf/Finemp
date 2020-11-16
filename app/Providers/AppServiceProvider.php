<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Supplier;
use Illuminate\Support\Str;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // compatibilizacao com o Laravel 8
        Paginator::useBootstrap();

        //evento executado antes de se criar o model no banco de dados
        Supplier::creating(function ($model) {
            $model->uuid = Str::orderedUuid();
        });
    }
}
