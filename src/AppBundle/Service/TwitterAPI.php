<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\User;

/**
* TODO: 全てのメソッドがコンストラクタでinjectしたUserオブジェクトを対象にしているので、Cronで全ユーザーにfindIdRangeByDate()を実行したい処理がやりにくい
*/

class TwitterAPI
{
    /**
    * @var Doctrine\Bundle\DoctrineBundle\Registry
    */
    protected $doctrine;

    /**
    * @var AppBundle\Entity\User
    */
    public $user;

    protected $consumer_key = ''; // api key
    protected $consumer_secret = ''; // api secret
    protected $bearer_token = '';

    protected $request_url = ''; // decide by api call method

    /**
     * @param User $user
     * @param array $key_and_token twitter api key and tokens
     */
    public function __construct(Registry $doctrine, User $user, array $key_and_token)
    {
        $this->doctrine = $doctrine;
        $this->user = $user;
        $this->consumer_key = $key_and_token['consumer_key'];
        $this->consumer_secret = $key_and_token['consumer_secret'];
        $this->bearer_token = $key_and_token['bearer_token'];
    }

    /**
     * get today timeline json
     *
     * @return array|null timeline or null
     */
    public function getTodayTimeline()
    {
        $get_query = ['user_id' => $this->user->getTwitterId()];
        $today = (new \DateTime())->format('Y-m-d');
        $since_id_at = $this->user->getSinceIdAt();

        // 今日の始点ツイートのsince_idが無ければsince_idを計算後、timlineを返す
        if ($since_id_at === null || $since_id_at->format('Y-m-d') !== $today) {

            $result = $this->findIdRangeByDate(new \DateTime(), $get_query);
            // エラーメッセージの場合
            if (isset($result['error'])) {
                return $result;
            }

            // since_idのDB登録
            $user = $this->user;
            $user->setTodaySinceId($result['since_id']);
            $user->setSinceIdAt(new \DateTime());
            $user->setUpdateAt(new \DateTime());
            $em = $this->doctrine->getEntityManager();
            $user = $em->merge($user);
            $em->persist($user);
            $em->flush();

            return $result['timeline_json'];
        }

        // since_idがあればget_queryに指定して今日のつぶやき一覧をapiから取得
        if ('undefined' !== $today_since_id = $this->user->getTodaySinceId()) {
            $get_query['since_id'] = $today_since_id;
        // since_idがundefinedなら200件まで取得 今日以前のつぶやきが存在しないアカウントなど
        } else {
            $get_query['count'] = '200';
        }
        $timeline = $this->callStatusesUserTimeline($get_query);

        return $timeline;
    }

    /**
     * get since_id ~ max_id range by target date
     *
     * @param \DateTme $targetDate must be up to 6 days ago. because twitter api limit.
     * @param array $get_query
     * @return array ['since_id' => '1234', 'max_id' => '5678', 'timeline_json' => decoded_json] or ['error' => 'error msg']
     */
    public function findIdRangeByDate(\DateTime $targetDate, array $get_query = [])
    {
        $target_day = $targetDate->format('Y-m-d');
        $saved_timeline = array(); // 今までに取得したtimeline
        $index = 0; // for文を回した回数 apiから20件づつ取得、forを回すという流れなのでこの変数は処理したtweetの合計数 - 1となる
        $max_id = null;
        $since_id = null;
        $get_query = array_merge($get_query, ['user_id' => $this->user->getTwitterId()]);

        while (true) {
            // timeline取得apiを叩く 2回目以降はmax_idで指定したつぶやき自身も含まれるので切り捨てる(指定max_id未満が欲しいので)
            $fetch_timeline = $index === 0 ? $this->callStatusesUserTimeline($get_query) : array_slice($this->callStatusesUserTimeline($get_query), 1);
            $saved_timeline = array_merge($saved_timeline, $fetch_timeline);

            // apiからの取得件数が0件
            if (count($fetch_timeline) < 1) {
                // timelineの総取得総数が0件 一件もつぶやきが無い人など
                if (count($saved_timeline) < 1) {
                    return ['error' => 'timeline get count 0.'];
                // apiの取得範囲制限内で指定日のsince_idが見つからない
                } else {
                    return ['since_id' => 'undefined', 'max_id' => $max_id, 'timeline_json' => $saved_timeline];
                }
            }

            for ($i=$index; count($saved_timeline) > $i; $i++) {
                // tweet1件についての情報が格納されたオブジェクト
                $tweet = $saved_timeline[$i];
                // 投稿日時 GMTで取得されるので日本のタイムゾーンに変換し、yyyy-mm-dd形式の文字列に整形
                $created_day = (new \DateTime($tweet->created_at))->setTimezone(new \DateTimeZone('Asia/Tokyo'))->format('Y-m-d');

                // 指定日のtweetが一件もなかった場合
                if ($max_id === null && $target_day > $created_day) {
                    return ['error' => 'target days tweet not found.'];
                }

                // 指定日の一番最後のtweetをmax_idとしてセット
                if ($max_id === null && $target_day === $created_day) {
                    $max_id = $tweet->id_str;
                    $max_id_index = $index;
                }

                // 指定日一日前の最初のtweetのsice_idとしてセット
                if ($target_day > $created_day) {
                    $since_id = $tweet->id_str;
                    // 今までapiから取得したtimelineからmax_id ~ (since_id - 1)の範囲を取得する
                    $target_day_timeline = array_slice($saved_timeline, $max_id_index, ($index - $max_id_index));

                    return ['since_id' => $since_id, 'max_id' => $max_id, 'timeline_json' => $target_day_timeline];
                }

                $index++;
            }
            // api次回取得位置を指定
            $get_query = array_merge($get_query, ['max_id' => $tweet->id_str, 'count' => '21']);
        }
    }

    /**
     * get timeline since_id from max_id
     *
     * @param string $since_id
     * @param string $max_id
     * @return array|null timeline or null
     */
    public function getTimelineSinceFromMax($since_id, $max_id)
    {
      if (!is_string($since_id) || !is_string($max_id)) {
          throw new InvalidArgumentException('TwitterAPI::getTimelineSinceFromMax() arguments must be string.');
      }

      $get_query = [
        'user_id' => $this->user->getTwitterId(),
        'since_id' => $since_id,
        'max_id' =>  $max_id,
      ];

      $decoded_json = $this->callStatusesUserTimeline($get_query);

      return $decoded_json;
    }

    /**
    * call api https://api.twitter.com/1.1/statuses/user_timeline.json
    *
    * @param array $get_query
    * @return stdClass $decoded_json
    */
    protected function callStatusesUserTimeline(array $get_query = array())
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
    * call api https://api.twitter.com/1.1/search/tweets.json
    *
    * @param array $get_query
    * @return stdClass $decoded_json
    */
    protected function callSearchTweets(array $get_query = array())
    {
        $this->request_url = 'https://api.twitter.com/1.1/search/tweets.json';

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
     * Get the value of User
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the value of User
     *
     * @return mixed
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
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
