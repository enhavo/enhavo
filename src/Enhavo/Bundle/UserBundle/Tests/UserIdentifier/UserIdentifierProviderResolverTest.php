<?php

namespace Enhavo\Bundle\UserBundle\Tests\UserIdentifier;

use Enhavo\Bundle\ResourceBundle\Tests\Mock\ContainerMock;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderInterface;
use Enhavo\Bundle\UserBundle\UserIdentifier\UserIdentifierProviderResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserIdentifierProviderResolverTest extends TestCase
{
    private function createDependencies()
    {
        $dependency = new UserIdentifierProviderResolverDependencies();
        $dependency->container = new ContainerMock();
        return $dependency;
    }

    private function createInstance(UserIdentifierProviderResolverDependencies $dependencies): UserIdentifierProviderResolver
    {
        $instance = new UserIdentifierProviderResolver($dependencies->userIdentifiers);
        $instance->setContainer($dependencies->container);
        return $instance;
    }

    public function testGetProvider()
    {
        $dependency = $this->createDependencies();

        $enhavoProvider = new Provider();
        $appProvider = new Provider();

        $dependency->container->set('enhavoProvider', $enhavoProvider);
        $dependency->container->set('appProvider', $appProvider);

        $dependency->userIdentifiers = [
            EnhavoUser::class => 'enhavoProvider',
            AppUser::class => 'appProvider',
        ];

        $resolver = $this->createInstance($dependency);

        $this->assertTrue($resolver->getProviderByClass(AppUser::class) === $appProvider);
        $this->assertTrue($resolver->getProvider(new AppUser) === $appProvider);

        $this->assertTrue($resolver->getProviderByClass(EnhavoUser::class) === $enhavoProvider);
        $this->assertTrue($resolver->getProvider(new EnhavoUser()) === $enhavoProvider);
    }

    public function testGetProviderWithParentClass()
    {
        $dependency = $this->createDependencies();
        $enhavoProvider = new Provider();
        $dependency->container->set('enhavoProvider', $enhavoProvider);

        $dependency->userIdentifiers = [
            EnhavoUser::class => 'enhavoProvider',
        ];

        $resolver = $this->createInstance($dependency);

        $this->assertTrue($resolver->getProviderByClass(AppUser::class) === $enhavoProvider);
        $this->assertTrue($resolver->getProvider(new AppUser) === $enhavoProvider);
    }

    public function testGetProviders()
    {
        $dependency = $this->createDependencies();

        $enhavoProvider = new Provider();
        $appProvider = new Provider();

        $dependency->container->set('enhavoProvider', $enhavoProvider);
        $dependency->container->set('appProvider', $appProvider);

        $dependency->userIdentifiers = [
            EnhavoUser::class => 'enhavoProvider',
            AppUser::class => 'appProvider',
        ];

        $resolver = $this->createInstance($dependency);

        $this->assertTrue(in_array($enhavoProvider, $resolver->getProviders()));
        $this->assertTrue(in_array($appProvider, $resolver->getProviders()));
    }
}

class EnhavoUser implements UserInterface
{
    public function getRoles(): array
    {
        return [];
    }

    public function getPassword()
    {

    }

    public function eraseCredentials(): void
    {

    }

    public function getUsername()
    {

    }

    public function getUserIdentifier(): string
    {
        return 'myUsername';
    }
}

class AppUser extends EnhavoUser
{

}

class Provider implements UserIdentifierProviderInterface
{
    public function getUserIdentifier(UserInterface $user): string
    {
        return $user->identifier;
    }

    public function getUserIdentifierByPropertyValues(array $values): string
    {
        return $values['identifier'];
    }

    public function getUserIdentifierProperties(): array
    {
        return ['identifier'];
    }
}

class UserIdentifierProviderResolverDependencies
{
    public array $userIdentifiers = [];
    public ContainerInterface $container;
}
