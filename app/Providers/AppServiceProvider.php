<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Medicine;
use App\Observers\MedicineObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

  public function boot()
{
    Medicine::observe(MedicineObserver::class);
}
}
