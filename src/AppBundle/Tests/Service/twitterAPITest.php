<?php

namespace AppBundle\Tests\Service;

use AppBundle\Service\TwitterAPI;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Prophecy\Argument\Token\AnyValuesToken;

class TwitterAPITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppBundle\Service\TwitterAPI
     */
    protected $twitterApi;

    /**
     * @var HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken
     */
    protected $oauthToken;

    protected $params = array();

    public function setUp()
    {
        $this->oauthToken = new OAuthToken('access_token', array('ROLE_TEST'));
        $this->oauthToken->setResourceOwnerName('github');
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($this->oauthToken);

        $this->params = array(
          'consumer_key' => 'consumer_key_value',
          'consumer_secret' => 'consumer_secret_value',
          'bearer_token' => 'bearer_token_value',
        );

        $this->twitterApi = new TwitterAPI($tokenStorage, $this->params);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAcceptOAuthTokenOnly()
    {
        $mockToken = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($mockToken);

        $twitterApi = new TwitterAPI($tokenStorage, $this->params);
    }

    /**
     * @test
     */
    public function getOauthToken()
    {
        $this->assertSame($this->twitterApi->getOauthToken(), $this->oauthToken);
    }

    /**
     * @test
     */
    public function getConsumerKey()
    {
        $this->assertSame($this->twitterApi->getConsumerKey(), $this->params['consumer_key']);
    }

    /**
     * @test
     */
    public function getConsumerSecret()
    {
        $this->assertSame($this->twitterApi->getConsumerSecret(), $this->params['consumer_secret']);
    }

    /**
     * @test
     */
    public function getBearerToken()
    {
        $this->assertSame($this->twitterApi->getBearerToken(), $this->params['bearer_token']);
    }
}
