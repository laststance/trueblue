<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Service\TwitterAPIClient;
use Phake;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MyControllerTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    protected function reload()
    {
        $this->client = static::createClient();
    }

    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $user = $this->client->getContainer()->get('doctrine')->getManager()->getRepository('AppBundle:User')->findOneByUsername('malloc007');
        $firewall = 'secured_area';
        $token = new UsernamePasswordToken($user, null, $firewall, ['ROLE_OAUTH_USER']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    /**
     * Prevents 500 errors by connecting to the real API.
     */
    protected function setTwitterAPIClientMock()
    {
        $mock = Phake::mock(TwitterAPIClient::class);
        Phake::when($mock)->getStatusesUserTimeline(Phake::anyParameters())->thenReturn($this->getFixture());
        $this->client = self::createClient();
        $this->client->getContainer()->get('twitter_api')->setApi($mock);
    }

    protected function getFixture(): array
    {
        require __DIR__.'/../DataFixtures/statusesUserTimelineFixture.php';

        return $statusesUserTimelineFixture;
    }
}
