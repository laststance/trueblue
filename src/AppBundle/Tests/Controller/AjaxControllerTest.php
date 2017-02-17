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
        $this->client->request('GET', '/ajax/malloc007/2017-01-21');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('[{"created_at":"Sat Jan 21 11:52:08 +0000 2017",', $this->client->getResponse()->getContent());

        // login
        $this->logIn();
        $this->client->request('GET', '/ajax/malloc007/2017-01-21');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('[{"created_at":"Sat Jan 21 11:52:08 +0000 2017",', $this->client->getResponse()->getContent());

        // undefined date
        $this->client->request('GET', '/ajax/malloc007/2200-01-10');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('[]', $this->client->getResponse()->getContent());

        // undefined user
        $this->client->request('GET', '/ajax/nonon/2200-01-10');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        // invalid date format
        $this->client->request('GET', '/ajax/nonon/22000110');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
