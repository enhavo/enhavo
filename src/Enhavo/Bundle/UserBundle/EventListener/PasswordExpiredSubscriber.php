<?php
/**
 * @author blutze-media
 * @since 2022-07-13
 */

/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PasswordExpiredSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager,
        private ConfigurationProvider $configurationProvider,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::PRE_AUTH => 'onPreAuth',
        ];
    }

    public function onPreAuth(UserEvent $event): void
    {
        $user = $event->getUser();
        if ($user instanceof UserInterface && $this->isPasswordExpired($user)) {
            $exception = new PasswordExpiredException('Password expired');
            $exception->setUser($user);
            $event->setException($exception);
            $this->userManager->resetPassword($user, $this->configurationProvider->getResetPasswordRequestConfiguration());
        }
    }

    private function isPasswordExpired(UserInterface $user): bool
    {
        $configuration = $this->configurationProvider->getLoginConfiguration();
        $updated = $user->getPasswordUpdatedAt() ? clone $user->getPasswordUpdatedAt() : null;

        return
            $configuration->getPasswordMaxAge() &&
            (!$updated || $updated->modify(sprintf('+%s', $configuration->getPasswordMaxAge())) < new \DateTime())
        ;
    }
}
