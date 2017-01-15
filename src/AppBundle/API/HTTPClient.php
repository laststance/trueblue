<?php

namespace AppBundle\API;

use GuzzleHttp\Client;

/**
 * TODO: remove
 * GuzzleHttp\Client shortcut wrapper.
 */
class HTTPClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 10.0]);
    }

    public function get(string $url, array $options = [])
    {
        $res = $this->client->get($url, $options);

        return $res->getBody()->getContents();
    }
}
