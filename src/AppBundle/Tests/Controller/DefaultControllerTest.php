<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    use ControllerTestUtilTrait;

    private $client;

    public function testIndex()
    {
        $this->client = static::createClient();

        // 未ログイン
        $crawler = $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // ログイン @TODO: 500を返すので原因を調査する
        //$this->logIn();
        //$crawler = $this->client->request('GET', '/');
        //$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

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
