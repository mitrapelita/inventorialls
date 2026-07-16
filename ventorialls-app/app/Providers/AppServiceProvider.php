<?php

namespace App\Providers;

use App\Models\Transaction;
use Illuminate\Support\Facades\View;
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
        if (str_contains(request()->getHost(), 'ngrok') || str_contains(request()->getHost(), 'trycloudflare.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('components.header', function ($view) {
            $pendingValidations = Transaction::where('status', 'menunggu_validasi')
                ->latest()
                ->get();
            $view->with('pendingValidations', $pendingValidations);
        });
    }
}
