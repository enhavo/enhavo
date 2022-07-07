<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\PreAuth;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Configuration\Login\LoginConfiguration;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractPreAuthSubscriber implements EventSubscriberInterface
{
    protected RequestStack          $requestStack;
    protected ConfigurationProvider $configurationProvider;

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
