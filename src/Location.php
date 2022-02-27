<?php

namespace Jeoip\Client;

use Exception;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Location
{
    public static function fromResponse(ResponseInterface $response): self
    {
        $content = $response->getContent();
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($data)) {
            throw new Exception('data is not an array');
        }
        if (!isset($data['status'])) {
            throw new Exception('status is missing');
        }
        if (!$data['status']) {
            if (isset($data['message']) and is_string($data['message'])) {
                throw new Exception($data['message']);
            }
            throw new Exception('status is not true');
        }
        if (!isset($data['country']) or !is_string($data['country'])) {
            throw new Exception("'country' is not valid");
        }
        if (!isset($data['countryCode']) or !is_string($data['countryCode'])) {
            throw new Exception("'countryCode' is not valid");
        }
        if (!isset($data['subnet']) or !is_string($data['subnet'])) {
            throw new Exception("'subnet' is not valid");
        }
        if (!isset($data['query']) or !is_string($data['query'])) {
            throw new Exception("'query' is not valid");
        }

        return new self($data['country'], $data['countryCode'], $data['subnet'], $data['query']);
    }

    protected string $country;
    protected string $countryCode;
    protected string $subnet;
    protected string $query;

    public function __construct(
        string $country,
        string $countryCode,
        string $subnet,
        string $query
    ) {
        $this->country = $country;
        $this->countryCode = $countryCode;
        $this->subnet = $subnet;
        $this->query = $query;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function getSubnet(): string
    {
        return $this->subnet;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
