<?php

namespace AppBundle\API;

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

    // #TODO: endpoint
    private $requestUrl = ''; // decide by api call method

    public function __construct(array $config)
    {
        $this->client = new Client(['timeout' => 10.0]);
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
     * @return stdClass $decoded_json
     */
    public function callStatusesUserTimeline(array $getQuery = [])
    {
        $this->requestUrl = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

        if ($getQuery) {
            $this->requestUrl = $this->concatGetQuery($this->requestUrl, $getQuery);
        }

        $response = $this->get($this->requestUrl, $this->createHeader());

        return $response;
    }

    /**
     * GET search/tweets.
     *
     * https://dev.twitter.com/rest/reference/get/search/tweets
     *
     * @param array $getQuery
     *
     * @return stdClass $decoded_json
     */
    public function callSearchTweets(array $getQuery = [])
    {
        $this->requestUrl = 'https://api.twitter.com/1.1/search/tweets.json';

        if ($getQuery) {
            $this->requestUrl = $this->concatGetQuery($this->requestUrl, $getQuery);
        }

        $response = $this->get($this->requestUrl, $this->createHeader());

        return $response;
    }

    /**
     * call api to get request.
     *
     * @param string $url
     * @param array  $options
     *
     * @throws TwitterAPICallException
     *
     * @return array $decoded_json
     */
    private function get(string $url, array $options = [])
    {
        try {
            $response = $this->client->get($url, $options)->getBody()->getContents();
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
     * @param string $getQuery
     *
     * @return string $request_url_with_query
     */
    private function concatGetQuery($requestUrl, $getQuery)
    {
        $requestUrlWithQuery = $requestUrl.'?'.http_build_query($getQuery);

        return $requestUrlWithQuery;
    }

    /**
     * create http header.
     *
     * @return array context
     */
    private function createHeader()
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer '.$this->bearerToken, // create bearer_token authrization header
            ],
        ];
    }
}
