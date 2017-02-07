<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\Traits\FixtureTrait;

class DefaultControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/user.yml'];

    protected $client;

    public function testIndex()
    {
        $this->client = static::createClient();

        // 未ログイン
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // ログイン
        $this->logIn();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // ログインページが表示されること
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('body.login')->count() > 0);
    }
}
