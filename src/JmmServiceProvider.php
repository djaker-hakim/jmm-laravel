<?php

namespace stm\jmmLaravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class JmmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('JMM', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...
            return new JmmUserProvider($config['model']);
        });
    }
}
