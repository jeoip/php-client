<?php

namespace Jeoip\Client\Tests;

use Jeoip\Client\Location;
use Jeoip\Common\Cidr;
use Jeoip\Common\Exceptions\Exception;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    public function test(): void
    {
        $location = $this->getLocation();

        $this->assertEquals('1.0.0.1', $location->getQuery());
        $this->assertEquals('XX', $location->getCountryCode());
        $this->assertEquals('1.0.0.0/24', $location->getSubnet()->__toString());
        $this->assertEquals('Name', $location->getCountryName()); // @phpstan-ignore-line
        $this->assertEquals('City Name', $location->getCity()); // @phpstan-ignore-line
        $this->assertFalse($location->hasData('state'));
        $this->assertIsArray($location->getExtraData());
    }

    public function testCallUndefinedMethod(): void
    {
        $this->expectException(\Error::class);
        $this->getLocation()->hello(); // @phpstan-ignore-line
    }

    public function testGetundefinedData(): void
    {
        $this->expectException(Exception::class);
        $this->getLocation()->getState(); // @phpstan-ignore-line
    }

    protected function getLocation(): Location
    {
        return new Location('1.0.0.1', 'XX', Cidr::parse('1.0.0.0/24'), [
            'countryName' => 'Name',
            'city' => 'City Name',
        ]);
    }
}
