<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractPreAuthSubscriber implements EventSubscriberInterface
{
    protected UserManager           $userManager;
    protected RequestStack          $requestStack;
    protected ConfigurationProvider $configurationProvider;

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::class => 'onPreAuth',
        ];
    }

    public function onPreAuth(UserEvent $event)
    {
        $harald = 'harald';
    }

    public function setUserManager(UserManager $userManager): void
    {
        $this->userManager = $userManager;
    }

    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    public function setConfigurationProvider(ConfigurationProvider $configurationProvider): void
    {
        $this->configurationProvider = $configurationProvider;
    }

    protected function getConfigKey()
    {
        return $this->requestStack->getCurrentRequest()->attributes->get('_config');
    }

    protected function getLoginConfiguration(): LoginConfiguration
    {
        return $this->configurationProvider->getLoginConfiguration($this->getConfigKey());
    }

    protected function checkPreAuth(UserEvent $event): void
    {

    }
}
