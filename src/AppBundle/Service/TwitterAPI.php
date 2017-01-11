<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Exception\TwitterAPICallException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

class TwitterAPI
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var User
     */
    public $user;

    protected $consumerKey = ''; // api key
    protected $consumerSecret = ''; // api secret
    protected $bearerToken = '';

    protected $requestUrl = ''; // decide by api call method

    /**
     * @var CommonService
     */
    private $commonService;

    public function __construct(
        Registry $doctrine,
        HTTPClient $client,
        array $keyAndToken,
        CommonService $commonService
    ) {
        $this->client = $client;
        $this->doctrine = $doctrine;
        $this->consumerKey = $keyAndToken['consumer_key'];
        $this->consumerSecret = $keyAndToken['consumer_secret'];
        $this->bearerToken = $keyAndToken['bearer_token'];
        $this->commonService = $commonService;
    }

    /**
     * get today timeline json.
     *
     * @return array|null timeline or null
     */
    public function getTodayTimeline()
    {
        $getQuery = ['user_id' => $this->user->getTwitterId()];
        $today = (new \DateTime())->format('Y-m-d');
        $sinceIdAt = $this->user->getSinceIdAt();

        // 今日の始点ツイートのsince_idが無ければsince_idを計算後、timlineを返す
        if ($sinceIdAt === null || $sinceIdAt->format('Y-m-d') !== $today) {
            $result = $this->findIdRangeByDate(new \DateTime(), $getQuery);
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
        $todaySinceId = $this->user->getTodaySinceId();
        if ($todaySinceId !== '') {
            $getQuery['since_id'] = $todaySinceId;
            // since_idが無ければ200件まで取得 今日以前のつぶやきが存在しないアカウントなど
        } else {
            $getQuery['count'] = '200';
        }
        $timeline = $this->callStatusesUserTimeline($getQuery);

        return $timeline;
    }

    /**
     * get since_id ~ max_id range by target date.
     *
     * @param \DateTime $targetDate must be up to 6 days ago. because twitter api limit
     * @param array     $getQuery
     *
     * @return array ['since_id' => '1234', 'max_id' => '5678', 'timeline_json' => decoded_json] or ['error' => 'error msg']
     */
    public function findIdRangeByDate(\DateTime $targetDate, array $getQuery = [])
    {
        $targetDay = $targetDate->format('Y-m-d');
        $savedTimeline = []; // 今までに取得したtimeline
        $index = 0; // for文を回した回数 apiから20件づつ取得、forを回すという流れなのでこの変数は処理したtweetの合計数 - 1となる
        $maxId = null;
        $sinceId = null;
        $getQuery = array_merge($getQuery, ['user_id' => $this->user->getTwitterId()]);

        while (true) {
            // timeline取得apiを叩く 2回目以降はmax_idで指定したつぶやき自身も含まれるので切り捨てる(指定max_id未満が欲しいので)
            $fetchTimeline = $index === 0 ? $this->callStatusesUserTimeline($getQuery) : array_slice(
                $this->callStatusesUserTimeline($getQuery),
                1
            );
            $savedTimeline = array_merge($savedTimeline, $fetchTimeline);

            // apiからの取得件数が0件
            if (count($fetchTimeline) < 1) {
                // timelineの総取得総数が0件 一件もつぶやきが無い人など
                if (count($savedTimeline) < 1) {
                    return ['error' => 'timeline get count 0.'];
                    // apiの取得範囲制限内で指定日のsince_idが見つからない
                } else {
                    return ['since_id' => 'undefined', 'max_id' => $maxId, 'timeline_json' => $savedTimeline];
                }
            }

            for ($i = $index; count($savedTimeline) > $i; ++$i) {
                // tweet1件についての情報が格納されたオブジェクト
                $tweet = $savedTimeline[$i];
                // 投稿日時 GMTで取得されるので日本のタイムゾーンに変換し、yyyy-mm-dd形式の文字列に整形
                $createdDay = (new \DateTime($tweet['created_at']))->setTimezone(
                    new \DateTimeZone('Asia/Tokyo')
                )->format('Y-m-d');

                // 指定日のtweetが一件もなかった場合
                if ($maxId === null && $targetDay > $createdDay) {
                    return ['error' => 'target days tweet not found.'];
                }

                // 指定日の一番最後のtweetをmax_idとしてセット
                if ($maxId === null && $targetDay === $createdDay) {
                    $maxId = $tweet['id_str'];
                    $maxIdIndex = $index;
                }

                // 指定日一日前の最初のtweetのsice_idとしてセット
                if ($targetDay > $createdDay) {
                    $sinceId = $tweet['id_str'];
                    // 今までapiから取得したtimelineからmax_id ~ (since_id - 1)の範囲を取得する
                    $targetDayTimeline = array_slice($savedTimeline, $maxIdIndex, ($index - $maxIdIndex));

                    return ['since_id' => $sinceId, 'max_id' => $maxId, 'timeline_json' => $targetDayTimeline];
                }

                ++$index;
            }
            // api次回取得位置を指定
            $getQuery = array_merge($getQuery, ['max_id' => $tweet['id_str'], 'count' => '21']);
        }
    }

    /**
     * get timeline since_id from max_id.
     *
     * @param string $sinceId
     * @param string $maxId
     *
     * @return array|null timeline or null
     */
    public function getTimelineSinceFromMax($sinceId, $maxId)
    {
        if (!is_string($sinceId) || !is_string($maxId)) {
            throw new InvalidArgumentException('TwitterAPI::getTimelineSinceFromMax() arguments must be string.');
        }

        $getQuery = [
            'user_id' => $this->user->getTwitterId(),
            'since_id' => $sinceId,
            'max_id' => $maxId,
        ];

        $decodedJson = $this->callStatusesUserTimeline($getQuery);

        return $decodedJson;
    }

    /**
     * Get the value of User.
     *
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the value of User.
     *
     * @return mixed
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of Consumer Key.
     *
     * @return mixed
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    /**
     * Get the value of Consumer Secret.
     *
     * @return mixed
     */
    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    /**
     * Get the value of Bearer Token.
     *
     * @return mixed
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * call api https://api.twitter.com/1.1/statuses/user_timeline.json.
     *
     * @param array $getQuery
     *
     * @return stdClass $decoded_json
     */
    protected function callStatusesUserTimeline(array $getQuery = [])
    {
        $this->requestUrl = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

        if ($getQuery) {
            $this->requestUrl = $this->concatGetQuery($this->requestUrl, $getQuery);
        }

        $response = $this->get($this->requestUrl, $this->createHeader());

        return $response;
    }

    /**
     * call api https://api.twitter.com/1.1/search/tweets.json.
     *
     * @param array $getQuery
     *
     * @return stdClass $decoded_json
     */
    protected function callSearchTweets(array $getQuery = [])
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
            $response = $this->client->get($url, $options);
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
