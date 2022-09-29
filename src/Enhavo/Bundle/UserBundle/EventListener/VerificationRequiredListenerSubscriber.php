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
use Enhavo\Bundle\UserBundle\Exception\VerificationRequiredException;
use Enhavo\Bundle\UserBundle\Model\UserInterface;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class VerificationRequiredListenerSubscriber implements EventSubscriberInterface
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
            UserEvent::POST_AUTH => 'onPostAuth',
        ];
    }

    public function onPostAuth(UserEvent $event): void
    {
        if ($this->isVerificationRequired($event->getUser())) {
            $exception = new VerificationRequiredException('Verification required');
            $exception->setUser($event->getUser());
            $event->setException($exception);
            $this->userManager->requestVerification($event->getUser(), $this->configurationProvider->getVerificationRequestConfiguration());
        }
    }

    private function isVerificationRequired(UserInterface $user): bool
    {
        $configuration = $this->configurationProvider->getLoginConfiguration();
        return $configuration->isVerificationRequired() && !$user->isVerified() ;
    }
}
