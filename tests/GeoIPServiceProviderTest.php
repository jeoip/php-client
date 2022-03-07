<?php

namespace Jeoip\Client\Tests;

use Illuminate\Container\Container;
use Jeoip\Client\Laravel\GeoIPServiceProvider;
use Jeoip\Contracts\IGeoIPService;
use PHPUnit\Framework\TestCase;

class GeoIPServiceProviderTest extends TestCase
{
    public function test(): void
    {
        $app = Container::getInstance();
        $serviceProvider = new GeoIPServiceProvider($app);
        $serviceProvider->register();
        $this->assertTrue($app->has(IGeoIPService::class));
        $this->assertEquals([IGeoIPService::class], $serviceProvider->provides());
    }
}
