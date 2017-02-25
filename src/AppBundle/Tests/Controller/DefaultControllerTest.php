<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\Controller\Traits\FixtureTrait;

class DefaultControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    public function testIndex()
    {
        /* @NomalScenario */
        // when not login, expect show 'Login with Twitter' button
        $this->reload();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Login with Twitter', $this->client->getResponse()->getContent());

        /* @NomalScenario */
        // when login, expect show 'Home' button
        $this->reload();
        $this->logIn();
        $this->client->request('GET', '/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Home', $this->client->getResponse()->getContent());

        /* @NomalScenario */
        // when browser lang is 'ja', expect translate ja
        $this->reload();
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ja,en-US;q=0.8,en;q=0.6']);
        $this->assertContains('Daily Tweetはtwitter上の自分のつぶやきを日別にまとめるサービスです。', $this->client->getResponse()->getContent());
        $this->assertContains('このサイトについて', $this->client->getResponse()->getContent());

        /* @NomalScenario */
        // when browser lang is 'en', expect translate en
        $this->reload();
        $this->client->request('GET', '/', [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.8,ja;q=0.6']);
        $this->assertContains('Daily Tweet is archive tweets on every other day.', $this->client->getResponse()->getContent());
        $this->assertContains('about', $this->client->getResponse()->getContent());
    }

    public function testHome()
    {
        /* @NomalScenario */
        // when not login & valid username, expect specific userpage
        $this->reload();
        $this->client->request('GET', '/malloc007');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /* @NomalScenario */
        // when logined & valid username, expect specific userpage
        $this->reload();
        $this->logIn();
        $this->client->request('GET', '/malloc007');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        /* @ExceptionScenario */
        // when undefined username, except redirect to index
        $this->reload();
        $this->client->request('GET', '/foo');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals('indexpage', $this->client->getRequest()->get('_route'));
    }
}
