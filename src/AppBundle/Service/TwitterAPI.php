<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class TwitterAPI
{
    /**
     * @var HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken
     */
    protected $oauth_token;

    protected $consumer_key = ''; // api key
    protected $consumer_secret = ''; // api secret

    /**
     * @param TokenStorage $token_storage $this->container->get('security.token_storage')
     * @param array $key_and_token twitter api key and tokens
     */
    public function __construct(TokenStorage $token_storage, array $key_and_token)
    {
        if (($oauth_token = $token_storage->getToken()) instanceof OAuthToken === false) {
            throw new InvalidArgumentException(sprintf('Object get from tokenstrage was not a OAuthToken. getting "%s" object.', get_class($oauth_token)));
        }

        $this->oauth_token = $oauth_token;
        $this->consumer_key = $key_and_token['consumer_key'];
        $this->consumer_secret = $key_and_token['consumer_secret'];
    }

    /**
     * @return string BearerToken
     */
    public function getBearerToken()
    {
        $api_key = $this->consumer_key;
        $api_secret = $this->consumer_secret;

        // クレデンシャルを作成
        $credential = base64_encode($api_key . ':' . $api_secret);

        // リクエストURL
        $request_url = 'https://api.twitter.com/oauth2/token';

        // リクエスト用のコンテキストを作成する
        $context = array(
          'http' => array(
            'method' => 'POST',
            'header' => array(
              'Authorization: Basic ' . $credential,
              'Content-Type: application/x-www-form-urlencoded;charset=UTF-8' ,
            ),
            'content' => http_build_query(array( 'grant_type' => 'client_credentials')),
          ),
        );

        $response_json = @file_get_contents($request_url, false, stream_context_create($context));
        $decoded_json = json_decode($response_json);

        if ($decoded_json->token_type !== 'bearer') {
            throw new \Exeption('faild to get the BearerToken');
        }

        return $decoded_json->access_token;
    }
}
