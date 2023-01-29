<?php

namespace Jeoip\Client;

use Jeoip\Contracts\IGeoIPService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class API implements IGeoIPService
{
    public HttpClientInterface $client;
    public string $endPoint;

    public function __construct(string $endPoint = 'https://jeoip.ir/')
    {
        $this->endPoint = $endPoint;
        $this->client = HttpClient::create([
            'base_uri' => $this->endPoint,
        ]);
    }

    public function query(?string $ip = null): Location
    {
        $response = $this->client->request('GET', '/api/'.($ip ?? 'json'));

        return Location::fromResponse($response);
    }
}
