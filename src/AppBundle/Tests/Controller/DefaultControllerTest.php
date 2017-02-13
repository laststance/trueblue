<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Service\TwitterAPIClient;
use AppBundle\Tests\Controller\Traits\FixtureTrait;
use Phake;

class DefaultControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    protected $client;

    public function testHome()
    {
        $this->client = self::createClient();

        // 未ログイン
        $this->client->request('GET', '/home');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());


        // ログイン
        $mock = Phake::mock(TwitterAPIClient::class);
        Phake::when($mock)->getStatusesUserTimeline(Phake::anyParameters())->thenReturn($this->getFixture());
        $this->client = self::createClient();
        $this->client->getContainer()->get('twitter_api')->setApi($mock);

        $this->logIn();
        $this->client->request('GET', '/home');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function getFixture()
    {
        require __DIR__.'/../DataFixtures/statusesUserTimelineFixture.php';

        return $statusesUserTimelineFixture;
    }
}
