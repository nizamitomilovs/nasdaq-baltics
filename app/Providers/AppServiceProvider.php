<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\StockRepository\StockRepository;
use App\Repositories\StockRepository\StockRepositoryInterface;
use App\Repositories\UserRepository\UserRepository;
use App\Repositories\UserRepository\UserRepositoryInterface;
use App\Services\ApiService\ApiClient;
use App\Services\ApiService\ApiClientInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientInterface::class, GuzzleClient::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);

        $this->app->bind(ApiClientInterface::class, function ($app) {
            return new ApiClient(
                env('NASDAQ_URL'),
                $app->make(GuzzleClient::class),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
