<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author blutze
 */
class PasswordExpiredSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserManager $userManager,
        private ConfigurationProvider $configurationProvider,
    ) {
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
            $configuration->getPasswordMaxAge()
            && (!$updated || $updated->modify(sprintf('+%s', $configuration->getPasswordMaxAge())) < new \DateTime())
        ;
    }
}
