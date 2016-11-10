<?php

namespace AppBundle\Tests\Service;

use AppBundle\Service\TwitterAPI;
use Prophecy\Argument\Token\AnyValuesToken;
use AppBundle\Entity\User;

class TwitterAPITest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppBundle\Service\TwitterAPI
     */
    protected $twitterApi;

    protected $params = array();

    public function setUp()
    {
        $mockDoctrine = $this->getMock('Doctrine\Bundle\DoctrineBundle\Registry', array(), array(), '', false);
        $user = $this->getMock('AppBundle\Entity\User');
        $client = $this->getMock('AppBundle\Service\HTTPClient');

        $this->params = array(
          'consumer_key' => 'consumer_key_value',
          'consumer_secret' => 'consumer_secret_value',
          'bearer_token' => 'bearer_token_value',
        );

        $this->twitterApi = new TwitterAPI($mockDoctrine, $user, $client, $this->params);
    }

    /**
     * @test
     */
    public function getUser()
    {
        $this->assertTrue($this->twitterApi->getUser() instanceof User);
    }

    /**
     * @test
     */
    public function setUser()
    {
        $user = $this->getMock('AppBundle\Entity\User');
        $this->twitterApi->setUser($user);
        $this->assertSame($this->twitterApi->getUser(), $user);
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
