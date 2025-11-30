<?php

namespace App\Providers;

use App\Models\Notice;
use Illuminate\Support\ServiceProvider;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {  // Set up mpdf as PDF renderer
       View::share('notices', Notice::all());
    }
}
