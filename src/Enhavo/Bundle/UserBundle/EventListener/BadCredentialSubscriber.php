<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class BadCredentialSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::class => 'onUserEvent',
        ];
    }

    /**
     * @throws Exception
     */
    public function onUserEvent(UserEvent $userEvent): void
    {
        if ($userEvent->getType() === UserEvent::TYPE_LOGIN_FAILED) {
            $this->onLoginFailure($userEvent);
        }
    }

    /**
     * @throws Exception
     */
    private function onLoginFailure(UserEvent $event): void
    {
        $user = $event->getUser();
        $exception = $event->getException();

        if ($exception instanceof BadCredentialsException) {
            $user->setFailedLoginAttempts(1 + $user->getFailedLoginAttempts());
            $user->setLastFailedLoginAttempt(new \DateTime());
            $this->userManager->update($user);
        }
    }

}
