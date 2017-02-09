<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\Traits\FixtureTrait;

class DefaultControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    protected $client;

    public function testIndex()
    {
        $this->client = static::createClient();

        // 未ログイン
        $this->client->request('GET', '/home');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // ログイン
        $this->logIn();
        $this->client->request('GET', '/home');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
