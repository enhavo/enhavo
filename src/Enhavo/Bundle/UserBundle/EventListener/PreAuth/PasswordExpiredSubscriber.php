<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Exception\PasswordExpiredException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;

abstract class PasswordExpiredSubscriber extends AbstractPreAuthSubscriber
{
    public function checkPreAuth(UserEvent $event): void
    {
        if (true === $this->isPasswordExpired($event->getUser(), $this->getLoginConfiguration())) {
            $event->setException(new PasswordExpiredException('Password expired'));
        }
    }

    public function isPasswordExpired(UserInterface $user, LoginConfiguration $configuration): bool
    {
        $updated = $user->getPasswordUpdatedAt();

        if ($configuration->getPasswordMaxAge() &&
            (!$updated || $updated->modify(sprintf('+%s', $configuration->getPasswordMaxAge())) < new \DateTime())
        ) {
            return true;
        }

        return false;
    }

}
