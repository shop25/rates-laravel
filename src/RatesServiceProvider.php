<?php

namespace S25\RatesApiLaravel;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use S25\RatesApiClient\Client;
use S25\RatesApiClient\Contracts;
use S25\RatesApiClient\RateStorage;

class RatesServiceProvider extends ServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        $this->publishConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->registerConsoleCommands();
        $this->registerClientAndStore();
    }

    protected function mergeConfig(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'rates');
    }

    protected function getConfigPath(): string
    {
        return __DIR__ . '/../config/rates.php';
    }

    protected function registerConsoleCommands(): void
    {
        $this->commands([Commands\ImportRates::class]);
    }

    protected function registerClientAndStore(): void
    {
        $this->app->singleton(
            Contracts\Client::class,
            fn() => new Client(config('rates.service_url'))
        );

        $this->app->singleton(
            Contracts\RateStorage::class,
            fn(Application $app) => new RateStorage($app->get(Contracts\Client::class))
        );
    }

    protected function publishConfig(): void
    {
        if (!$this->isLumen()) {
            $this->publishes(
                [
                    $this->getConfigPath() => $this->app->configPath('rates.php'),
                ],
                'config'
            );
        }
    }

    protected function isLumen(): bool
    {
        return Str::contains($this->app->version(), 'Lumen');
    }
}
