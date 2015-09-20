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
    protected $oauthToken;

    protected $consumer_key = ''; // api key
    protected $consumer_secret = ''; // api secret
    protected $bearer_token = '';

    protected $request_url = ''; // decide by api call method

    /**
     * @param TokenStorage $token_storage $this->container->get('security.token_storage')
     * @param array $key_and_token twitter api key and tokens
     */
    public function __construct(TokenStorage $token_storage, array $key_and_token)
    {
        if (($oauthToken = $token_storage->getToken()) instanceof OAuthToken === false) {
            throw new InvalidArgumentException(sprintf('Object get from tokenstrage was not a OAuthToken. getting "%s" object.', get_class($oauthToken)));
        }

        $this->oauthToken = $oauthToken;
        $this->consumer_key = $key_and_token['consumer_key'];
        $this->consumer_secret = $key_and_token['consumer_secret'];
        $this->bearer_token = $key_and_token['bearer_token'];
    }

    /**
     * @return TBD
     */
    public function getTodaysTweet()
    {
        $database['todays_since_id'] = '';

        // 今日の始点つぶやきのsince_idが無ければタイムラインをまるまる取得してsince_idを計算する
        if (!$database['todays_since_id']) {
            $get_query = array(
              'user_id' => $this->oauthToken->getRawToken()['user_id'],
              'count' => '200',
            );
            $decoded_json = $this->getUserTimeline($get_query);
            //今日一番最初のつぶやきのsince_id抽出処理

            //抽出したsince_idをDBに登録

            //今日のつぶやき一覧をreturn

        // since_idがあればget_queryに指定して今日のつぶやき一覧をapiから取得
        } else {
            $get_query = array(
              'user_id' => $this->oauthToken->getRawToken()['user_id'],
              'count' => '200',
            );

            return $decoded_json = $this->getUserTimeline($get_query);
        }
    }

    /**
    * call api https://api.twitter.com/1.1/statuses/user_timeline.json
    *
    * @param array $get_query
    * @return stdClass $decoded_json
    */
    protected function getUserTimeline(array $get_query = array())
    {
        $this->request_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

        if ($get_query) {
            $this->request_url = $this->concatGetQuery($this->request_url, $get_query);
        }

        $context = $this->createBearerAuthContext();

        $response_json = @file_get_contents($this->request_url, false, stream_context_create($context));
        $decoded_json = json_decode($response_json);

        return $decoded_json;
    }

    /**
     * @param string date format 'Y-m-d'
     * @return json tweet
     */
    protected function getTweetByDate($date)
    {
        $this->request_url = 'https://api.twitter.com/1.1/search/tweets.json';
        $get_query = array(
          'q' => 'from:' . $this->oauthToken->getRawToken()['screen_name'] . ' since:'. $date,
          'include_entities' => 'true',
        );

        $this->request_url = $this->concatGetQuery($this->request_url, $get_query);

        $context = $this->createBearerAuthContext();

        $response_json = @file_get_contents($this->request_url, false, stream_context_create($context));
        $decoded_json = json_decode($response_json);

        return $decoded_json;
    }

    /**
     * create new BearerToken connect with twitter api
     * @return string BearerToken
     */
    public function createNewBearerToken()
    {
        $api_key = $this->consumer_key;
        $api_secret = $this->consumer_secret;

        // クレデンシャルを作成
        $credential = base64_encode($api_key . ':' . $api_secret);

        // リクエストURL
        $this->request_url = 'https://api.twitter.com/oauth2/token';

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

        $response_json = @file_get_contents($this->request_url, false, stream_context_create($context));
        $decoded_json = json_decode($response_json);

        if ($decoded_json->token_type !== 'bearer') {
            throw new \Exeption('faild to get the BearerToken');
        }

        return $decoded_json->access_token;
    }

    /**
    * concat encoded get_query to http_request_url
    * @param string $request_url
    * @param string $get_query
    * @return string $request_url_with_query
    */
    protected function concatGetQuery($request_url, $get_query)
    {
        $request_url_with_query = $request_url . '?' . http_build_query($get_query);

        return $request_url_with_query;
    }

    /**
    * create bearer_token authrization http_request_context
    * @return array context
    */
    protected function createBearerAuthContext()
    {
        return array(
                 'http' => array(
                   'method' => 'GET',
                   'header' => array(
                     'Authorization: Bearer ' . $this->bearer_token,
                   ),
                 ),
               );
    }

    /**
     * Get the value of Oauth Token
     *
     * @return HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken
     */
    public function getOauthToken()
    {
        return $this->oauthToken;
    }

    /**
     * Get the value of Consumer Key
     *
     * @return mixed
     */
    public function getConsumerKey()
    {
        return $this->consumer_key;
    }

    /**
     * Get the value of Consumer Secret
     *
     * @return mixed
     */
    public function getConsumerSecret()
    {
        return $this->consumer_secret;
    }

    /**
     * Get the value of Bearer Token
     *
     * @return mixed
     */
    public function getBearerToken()
    {
        return $this->bearer_token;
    }
}
