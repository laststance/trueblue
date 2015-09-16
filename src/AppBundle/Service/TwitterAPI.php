<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class TwitterAPI
{
    /**
     * @var HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken
     */
    protected $oauth_token;

    protected $consumer_key = '';
    protected $consumer_secret = '';

    /**
     * @param TokenStorage $token_storage $this->container->get('security.token_storage')
     * @param array $key_and_token twitter api key and tokens
     */
    public function __construct(TokenStorage $token_storage, array $key_and_token)
    {
        if (($oauth_token = $token_storage->getToken()) instanceof OAuthToken === false) {
            throw new InvalidArgumentException(sprintf('Object get from tokenstrage was not a OAuthToken. getting "%s" object.', get_class($oauth_token)));
        }

        $this->oauth_token = $oauth_token;
        $this->consumer_key = $key_and_token['consumer_key'];
        $this->consumer_secret = $key_and_token['consumer_secret'];
    }
}
