<?php

namespace Jeoip\Client\Laravel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Jeoip\Client\API;
use Jeoip\Contracts\IGeoIPService;

class GeoIPServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->singleton(IGeoIPService::class, API::class);
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return [IGeoIPService::class];
    }
}
