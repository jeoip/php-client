<?php

namespace Jeoip\Client\Tests;

use Jeoip\Client\API;
use Jeoip\Client\Location;
use PHPUnit\Framework\TestCase;

class APITest extends TestCase
{
    public function test(): void
    {
        $api = new API();
        $location = $api->query('1.1.1.1');
        $this->assertInstanceOf(Location::class, $location);
    }
}
