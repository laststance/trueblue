<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Exception\TwitterAPICallException;
use AppBundle\Service\TwitterAPIService;
use AppBundle\Tests\Controller\Traits\FixtureTrait;
use Phake;

class AjaxControllerTest extends MyControllerTestCase
{
    use FixtureTrait;

    public static $fixtures = [__DIR__.'/../DataFixtures/Alice/fixture.yml'];

    public function testDaily()
    {
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

    public function testInitialImport()
    {
        // when action successful, expect for 2 weeks tweet inserted DB
        $this->reload();
        $this->logIn();
        $mock = $this->set2WeeksTweetMock();
        $this->client->request('GET', '/ajax/initial/import');
        Phake::verify($mock, Phake::times(14))->findIdRangeByDate(Phake::anyParameters());
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"complate"', $this->client->getResponse()->getContent());
        $imported = $this->fetchImportedByTest();
        for ($i = 14; $i >= 1; --$i) {
            $this->assertEquals(['mock data No.'.$i], array_shift($imported)->getTimeline());
        }
        $this->cleanDB();

        // when not login, expect redirect to index
        $this->reload();
        $this->client->request('GET', '/ajax/initial/import');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->client->followRedirect();
        $this->assertEquals('indexpage', $this->client->getRequest()->get('_route'));

        // when user is already initial imported, expect API return "already imported"
        $this->reload();
        $this->logIn();
        $this->setImportState(true);
        $this->client->request('GET', '/ajax/initial/import');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('"already imported"', $this->client->getResponse()->getContent());

        // when thrown Exception on business logic, expect return 500 response
        $this->reload();
        $this->logIn();
        $mock = $this->setIntentionalExceptionMock();
        $this->setImportState(false);
        $this->client->request('GET', '/ajax/initial/import');
        Phake::verify($mock, Phake::times(1))->findIdRangeByDate(Phake::anyParameters());
        $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
    }

    protected function set2WeeksTweetMock()
    {
        $this->expectClient();

        $mock = Phake::mock(TwitterAPIService::class);
        for ($i = 1; $i <= 14; ++$i) {
            $d = new \DateTime($i.' days ago');
            Phake::when($mock)->findIdRangeByDate($d)->thenReturn(['timeline_json' => ['mock data No.'.$i]]);
        }
        $this->client->getContainer()->set('twitter_api', $mock);

        return $mock; // for Phake::verify()
    }

    protected function setIntentionalExceptionMock()
    {
        $this->expectClient();

        $mock = Phake::mock(TwitterAPIService::class);
        Phake::when($mock)->findIdRangeByDate(new \DateTime('1 days ago'))->thenThrow(new TwitterAPICallException(500));
        $this->client->getContainer()->set('twitter_api', $mock);

        return $mock;
    }

    protected function fetchImportedByTest(): array
    {
        $this->expectClient();

        $em = $this->getEntityManager();
        $repository = $em->getRepository('AppBundle:PastTimeline');
        $pastTimelines = $repository->findBy([], ['id' => 'DESC'], 14);

        return $pastTimelines;
    }

    protected function cleanDB()
    {
        $this->expectClient();

        $em = $this->getEntityManager();
        foreach ($this->fetchImportedByTest() as $i) {
            $em->remove($i);
        }
        $em->flush();
    }

    protected function setImportState(bool $bool)
    {
        $this->expectClient();

        $em = $this->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->findOneByUsername('malloc007');
        $user->setIsInitialTweetImport($bool);
        $em->persist($user);
        $em->flush();
    }
}
