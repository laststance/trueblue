<?php

namespace AppBundle\Tests\Service;

use AppBundle\Entity\User;
use AppBundle\Service\TwitterAPIClient;
use AppBundle\Service\TwitterAPIService;
use Phake;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TwitterAPIServiceTest extends WebTestCase
{
    /**
     * @var TwitterAPIService
     */
    private $twitterApiService;

    public function setUp()
    {
        $this->twitterApiService = self::createClient()->getContainer()->get('twitter_api');

        $mock = Phake::mock(TwitterAPIClient::class);
        Phake::when($mock)->getStatusesUserTimeline(Phake::anyParameters())->thenReturn($this->getFixture());
        $user = (new User())->setTwitterId(1664570156);

        $this->twitterApiService->setApi($mock);
        $this->twitterApiService->setUser($user);
    }

    public function testFindIdRangeByDate()
    {
        /** @NomalScenario */
        // when usertimeline(fetch from twitter API) contain '2017-01-11' tweet, expect return '2017-01-11' tweet collection
        $res = $this->twitterApiService->findIdRangeByDate(new \DateTime('2017-01-11'));

        $this->assertTrue(isset($res['since_id']));
        $this->assertTrue(isset($res['max_id']));
        $this->assertTrue(isset($res['timeline_json']));

        $this->assertEquals($res['since_id'], '818759358150426625');
        $this->assertEquals($res['max_id'], '819175659234701313');
        $this->assertEquals(count($res['timeline_json']), 27);

        $tweet = $res['timeline_json'][0];
        $this->assertEquals($tweet['created_at'], 'Wed Jan 11 13:34:31 +0000 2017');
        $this->assertEquals($tweet['id'], 819175659234701313);
        $this->assertEquals($tweet['text'], '@malloc007 fixture21');

        //** @NomalScenario */
        // when usertimeline(fetch from twitter API) not contain '2018-01-11' tweet, expect error message
        $res = $this->twitterApiService->findIdRangeByDate(new \DateTime('2018-01-11'));

        $this->assertEquals($res, ['error' => 'usertimeline(fetch from twitter API) not contain targetdate.']);

        //** @NomalScenario */
        // when could not fetch usertimeline from twitter API, expect error message
        $mock = Phake::mock(TwitterAPIClient::class);
        Phake::when($mock)->getStatusesUserTimeline(Phake::anyParameters())->thenReturn([]);
        $this->twitterApiService->setApi($mock);

        $res = $this->twitterApiService->findIdRangeByDate(new \DateTime('2017-01-11'));

        $this->assertEquals($res, ['error' => 'could not fetch any data of usertimeline, from twitter API.']);
    }

    public function getFixture()
    {
        require __DIR__.'/../DataFixtures/statusesUserTimelineFixture.php';

        return $statusesUserTimelineFixture;
    }
}
