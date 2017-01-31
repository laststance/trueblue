<?php
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MyControllerTestCase extends WebTestCase
{
    protected function logIn()
    {
        $session = $this->client->getContainer()->get('session');
        $firewall = 'secured_area';
        $token = new UsernamePasswordToken('malloc007', null, $firewall, ['ROLE_OAUTH_USER']);
        $session->set('_security_'.$firewall, serialize($token));
        $session->save();
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
