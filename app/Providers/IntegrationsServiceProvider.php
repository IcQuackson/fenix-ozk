<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use App\Contracts\FenixPort;
use App\Contracts\TokenProvider;
use App\Infrastructure\Fenix\FenixHttpClient;
use App\Infrastructure\Fenix\DatabaseTokenProvider;

class IntegrationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            TokenProvider::class,
            fn($app) =>
            new DatabaseTokenProvider()
        );

        $this->app->bind(FenixPort::class, function ($app) {
            $cfg = config('services.fenix');
            return new FenixHttpClient(
                baseUrl: $cfg['base_url'],
                accessTokenUrl: $cfg['access_token_url'],
                refreshTokenUrl: $cfg['refresh_token_url'],
                clientId: $cfg['client_id'],
                clientSecret: $cfg['client_secret'],
                tokens: $app->make(TokenProvider::class),
                cache: $app->make(CacheRepository::class),
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
