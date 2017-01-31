<?php
namespace AppBundle\Tests\Controller;

class DefaultControllerTest extends MyControllerTestCase
{
    private $client;

    public function testIndex()
    {
        $this->client = static::createClient();

        // 未ログイン
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
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
