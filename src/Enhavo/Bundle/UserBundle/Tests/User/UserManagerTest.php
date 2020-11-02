<?php
/**
 * @author blutze-media
 * @since 2020-11-02
 */

namespace Enhavo\Bundle\UserBundle\Tests\User;


use Enhavo\Bundle\UserBundle\Mapper\UserMapper;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private function createInstance($dependencies, $config, $mapping)
    {
        return new UserManager(
            $dependencies->entityManager,
            $dependencies->mailerManager,
            $dependencies->userRepository,
            $dependencies->getUserMapper($mapping),
            $dependencies->tokenGenerator,
            $dependencies->translator,
            $dependencies->formFactory,
            $dependencies->encoderFactory,
            $dependencies->router,
            $dependencies->eventDispatcher,
            $config
        );
    }

    private function createDependencies()
    {

    }

    public function testRegister()
    {
        $this->assertEquals('register', 'register');
    }
}

class UserManagerDependencies
{
    public function getUserMapper($config): UserMapper
    {
        return new UserMapper($config);
    }
}
