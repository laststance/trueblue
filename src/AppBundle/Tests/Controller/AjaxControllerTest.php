<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\Traits\FixtureTrait;

class AjaxControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    protected $client;

    public function testDaily()
    {
        $this->client = static::createClient();

        // no login
        $this->client->request('GET', '/ajax/daily/2017-01-10');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());

        // login
        $this->logIn();
        $this->client->request('GET', '/ajax/daily/2017-01-10');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
