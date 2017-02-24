<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Exception\TwitterAPICallException;
use AppBundle\Service\TwitterAPIService;
use AppBundle\Tests\Controller\Traits\FixtureTrait;
use Phake;
use Symfony\Bundle\FrameworkBundle\Client;

class AjaxControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    /** @var Client */
    protected $client;

    public function testDaily()
    {
        $this->client = static::createClient();

        // no login
        $this->reload();
        $this->client->request('GET', '/ajax/malloc007/2017-01-21');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('[{"created_at":"Sat Jan 21 11:52:08 +0000 2017",', $this->client->getResponse()->getContent());

        // login
        $this->reload();
        $this->logIn();
        $this->client->request('GET', '/ajax/malloc007/2017-01-21');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('[{"created_at":"Sat Jan 21 11:52:08 +0000 2017",', $this->client->getResponse()->getContent());

        // undefined date
        $this->reload();
        $this->client->request('GET', '/ajax/malloc007/2200-01-10');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('""', $this->client->getResponse()->getContent());

        // undefined user
        $this->reload();
        $this->client->request('GET', '/ajax/nonon/2200-01-10');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        // invalid date format
        $this->reload();
        $this->client->request('GET', '/ajax/nonon/22000110');
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testInitialImportNotLogin()
    {
        $this->setTwitterAPIClientMock();
        $this->client->request('GET', '/ajax/initial/import');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals('indexpage', $this->client->getRequest()->get('_route'));
    }

    public function testInitialImportSuccess()
    {
        $mock = $this->prepareTrueResponse();
        $this->client->request('GET', '/ajax/initial/import');
        Phake::verify($mock, Phake::times(14))->findIdRangeByDate(Phake::anyParameters());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"complate"', $this->client->getResponse()->getContent());
        $imported = $this->fetchImportedByTest();
        for ($i = 14; $i >= 1; --$i) {
            $this->assertEquals(['mock data No.'.$i], array_shift($imported)->getTimeline());
        }
        $this->cleanDB();
    }

    public function testInitialImportAlreadyImport()
    {
        $this->reload();
        $this->logIn();
        $this->setInportState(true);
        $this->client->request('GET', '/ajax/initial/import');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"already imported"', $this->client->getResponse()->getContent());
    }

    public function testInitialImportFaild()
    {
        $mock = $this->prepareFaildResponse();
        $this->setInportState(false);
        $this->client->request('GET', '/ajax/initial/import');
        Phake::verify($mock, Phake::times(1))->findIdRangeByDate(Phake::anyParameters());
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    protected function prepareTrueResponse()
    {
        $mock = $this->setTrueResponseMock();

        $this->client = self::createClient();
        $this->logIn();
        $this->client->getContainer()->set('twitter_api', $mock);

        return $mock; // for Phake::verify()
    }

    /**
     * Set a mock for two weeks.
     */
    protected function setTrueResponseMock()
    {
        $mock = Phake::mock(TwitterAPIService::class);
        for ($i = 1; $i <= 14; ++$i) {
            $d = new \DateTime($i.' days ago');
            Phake::when($mock)->findIdRangeByDate($d)->thenReturn(['timeline_json' => ['mock data No.'.$i]]);
        }

        return $mock;
    }

    protected function prepareFaildResponse()
    {
        $mock = $this->setFaildResponseMock();

        $this->reload();
        $this->logIn();
        $this->client->getContainer()->set('twitter_api', $mock);

        return $mock;
    }

    protected function setFaildResponseMock()
    {
        $mock = Phake::mock(TwitterAPIService::class);
        Phake::when($mock)->findIdRangeByDate(new \DateTime('1 days ago'))->thenThrow(new TwitterAPICallException(500));

        return $mock;
    }

    protected function fetchImportedByTest(): array
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        $repository = $em->getRepository('AppBundle:PastTimeline');
        $pastTimelines = $repository->findBy([], ['id' => 'DESC'], 14);

        return $pastTimelines;
    }

    protected function cleanDB()
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        foreach ($this->fetchImportedByTest() as $i) {
            $em->remove($i);
        }
        $em->flush();
    }

    protected function reload()
    {
        $this->client = static::createClient();
    }

    protected function setInportState(bool $bool)
    {
        $em = $this->client->getContainer()->get('doctrine.orm.default_entity_manager');
        $user = $em->getRepository('AppBundle:User')->findOneByUsername('malloc007');
        $user->setIsInitialTweetImport($bool);
        $em->persist($user);
        $em->flush();
    }
}
