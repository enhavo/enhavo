<?php
/**
 * @author blutze-media
 * @since 2020-10-26
 */

/**
 * @author blutze-media
 * @since 2020-10-26
 */

namespace Enhavo\Bundle\UserBundle\Security\Authentication;


use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Enhavo\Bundle\UserBundle\Event\UserLoginEvent;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait AuthenticationSuccessTrait
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    private function dispatch(TokenInterface $token)
    {
        /** @var UserInterface $user */
        $user = $token->getUser();
        $this->eventDispatcher->dispatch(UserEvents::LOGIN, new UserLoginEvent($user));
    }
}
