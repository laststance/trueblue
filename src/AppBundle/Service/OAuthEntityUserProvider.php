<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\EntityUserProvider;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class OAuthEntityUserProvider extends EntityUserProvider implements UserProviderInterface
{
    protected $em;

    public function __construct(ManagerRegistry $registry, $class, array $properties, $managerName = null)
    {
        parent::__construct($registry, $class, $properties, $managerName);
        $this->em = $registry->getManager($managerName);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        try {
            $resourceOwnerName = $response->getResourceOwner()->getName();

            if (!isset($this->properties[$resourceOwnerName])) {
                throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
            }

            $username = $response->getUsername();

            if (null === $user = $this->repository->findOneBy([$this->properties[$resourceOwnerName] => $username])) {
                throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
            }

            return $user;
        } catch (UsernameNotFoundException $e) {
            $rawResponse = $response->getResponse();

            $user = new User($rawResponse['screen_name']);
            $user->setTwitterId($rawResponse['id']);
            $user->setUsername($rawResponse['screen_name']);
            $user->setTodaySinceId('');
            $user->setIsActive(true);
            $user->setCreateAt(new \DateTime());
            $user->setUpdateAt(new \DateTime());
            $this->em->persist($user);
            $this->em->flush();

            return $user;
        }
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return 'AppBundle\\Entity\\OAuthUserProvider' === $class;
    }
}
