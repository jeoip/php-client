<?php

namespace Jeoip\Client;

use Error;
use Jeoip\Common\Cidr;
use Jeoip\Common\Exceptions\Exception;
use Jeoip\Common\Location as CommonLocation;
use Jeoip\Contracts\ICidr;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @phpstan-type ExtraDataType array<string,mixed>
 */
class Location extends CommonLocation
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
        $subnet = Cidr::parse($data['subnet']);
        $countryCode = $data['countryCode'];
        unset($data['countryCode'], $data['subnet'], $data['status']);

        /*
         * @var ExtraDataType $data
         */
        return new self($countryCode, $subnet, $data);
    }

    /**
     * @var ExtraDataType
     */
    protected array $extraData;

    /**
     * @param ExtraDataType $extraData
     */
    public function __construct(string $countryCode, ICidr $subnet, array $extraData = [])
    {
        parent::__construct($countryCode, $subnet);
        $this->extraData = $extraData;
    }

    /**
     * @param array<int,mixed> $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if ('get' != substr($name, 0, 3)) {
            throw new Error('Call to undefined method '.__CLASS__."::{$name}()");
        }
        $name = lcfirst(substr($name, 3));

        return $this->getData($name);
    }

    /**
     * @return mixed
     */
    public function getData(string $name)
    {
        if (!isset($this->extraData[$name])) {
            throw new Exception("There is no '{$name}' data");
        }

        return $this->extraData[$name];
    }

    public function hasData(string $name): bool
    {
        $name = lcfirst($name);

        return isset($this->extraData[$name]);
    }

    /**
     * @return ExtraDataType
     */
    public function getExtraData(): array
    {
        return $this->extraData;
    }
}
