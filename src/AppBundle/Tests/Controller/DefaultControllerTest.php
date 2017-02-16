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

    public function testIndex()
    {
        // not login
        $this->client = self::createClient();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Login with Twitter', $this->client->getResponse()->getContent());

        // login
        $this->client = self::createClient();
        $this->logIn();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Home', $this->client->getResponse()->getContent());

        // transtation
        $this->client = self::createClient();
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.8,en;q=0.6']);
        $this->assertContains('Daily Tweetはtwitter上の自分のつぶやきを日別にまとめるサービスです。', $this->client->getResponse()->getContent());
        $this->assertContains('このサイトについて', $this->client->getResponse()->getContent());

        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,ja;q=0.6']);
        $this->assertContains('Daily Tweet is archive tweets on every other day.', $this->client->getResponse()->getContent());
        $this->assertContains('about', $this->client->getResponse()->getContent());
    }

    public function testHome()
    {
        $this->client = self::createClient();

        // not login
        $this->client->request('GET', '/home');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // login
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
