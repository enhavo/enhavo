<?php

namespace Enhavo\Bundle\UserBundle\UserIdentifier;

use Enhavo\Bundle\UserBundle\Exception\UserIdentifierException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentifierProviderResolver
{
    use ContainerAwareTrait;

    public function __construct(
        private array $userIdentifiers
    )
    {
    }

    public function getProvider(UserInterface $user): UserIdentifierProviderInterface
    {
        return $this->getProviderByClass(get_class($user));
    }

    public function getProviderByClass($className): UserIdentifierProviderInterface
    {
        $originalClassName = $className;

        while ($className) {
            foreach ($this->userIdentifiers as $class => $provider) {
                if ($className === $class) {
                    return $this->container->get($provider);
                }
            }
            $className = get_parent_class($className);
        }

        throw new UserIdentifierException(sprintf('Can\'t resolve provider for class %s', $originalClassName));
    }

    /**
     * @return UserIdentifierProviderInterface[]
     */
    public function getProviders(): array
    {
        $providers = [];
        foreach ($this->userIdentifiers as $class => $provider) {
            $providers[] = $this->container->get($provider);
        }
        return $providers;
    }
}
