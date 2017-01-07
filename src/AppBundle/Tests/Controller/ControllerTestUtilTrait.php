<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait ControllerTestUtilTrait
{
    private function logIn()
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
