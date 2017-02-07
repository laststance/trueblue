<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class MyControllerTestCase extends WebTestCase
{
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
}
