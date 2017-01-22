<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class TwitterAPIClient
{
    /**
     * @var ClientInterface
     */
    private $client;

    private $consumerKey = ''; // api key
    private $consumerSecret = ''; // api secret
    private $bearerToken = '';

    public function __construct(array $config)
    {
        $this->client = new Client(['timeout' => 60.0]);
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];
        $this->bearerToken = $config['bearer_token'];
    }

    /**
     * GET statuses/user_timeline.
     *
     * https://dev.twitter.com/rest/reference/get/statuses/user_timeline
     *
     * @param array $getQuery
     *
     * @return array $response
     */
    public function getStatusesUserTimeline(array $getQuery = []): array
    {
        $endpoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

        $response = $this->get($endpoint, $getQuery, $this->createHeader());

        return $response;
    }

    /**
     * GET search/tweets.
     *
     * https://dev.twitter.com/rest/reference/get/search/tweets
     *
     * @param array $getQuery
     *
     * @return array $response
     */
    public function getSearchTweets(array $getQuery = []): array
    {
        $endpoint = 'https://api.twitter.com/1.1/search/tweets.json';

        $response = $this->get($endpoint, $getQuery, $this->createHeader());

        return $response;
    }

    /**
     * ClientInterface->get() wrapper.
     *
     * @param string $endpoint
     * @param array  $getQuery
     * @param array  $options
     *
     * @throws TwitterAPICallException
     *
     * @return array $decodedJson
     */
    private function get(string $endpoint, array $getQuery, array $options = []): array
    {
        if ($getQuery) {
            $requestUrl = $this->concatGetQuery($endpoint, $getQuery);
        }

        try {
            $response = $this->client->get($requestUrl, $options)->getBody()->getContents();
        } catch (RequestException $e) {
            throw new TwitterAPICallException(500, 'twitter api call faild.', $e);
        }

        $decodedJson = json_decode($response, true);

        return $decodedJson;
    }

    /**
     * concat encoded get_query to http_request_url.
     *
     * @param string $requestUrl
     * @param array  $getQuery
     *
     * @return string $request_url_with_query
     */
    private function concatGetQuery(string $requestUrl, array $getQuery): string
    {
        $requestUrlWithQuery = $requestUrl.'?'.http_build_query($getQuery);

        return $requestUrlWithQuery;
    }

    /**
     * create http header.
     *
     * @return array context
     */
    private function createHeader(): array
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer '.$this->bearerToken, // create bearer_token authrization header
            ],
        ];
    }
}
