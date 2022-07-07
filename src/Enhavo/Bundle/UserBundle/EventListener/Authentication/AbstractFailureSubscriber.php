<?php
/**
 * @author blutze-media
 * @since 2022-07-07
 */

namespace Enhavo\Bundle\UserBundle\EventListener\Authentication;

use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractFailureSubscriber implements EventSubscriberInterface
{
    protected UserManager           $userManager;
    protected RequestStack          $requestStack;
    protected ConfigurationProvider $configurationProvider;
    protected RouterInterface       $router;
    protected TranslatorInterface   $translator;

    public static function getSubscribedEvents(): array
    {
        return [
            UserEvent::class => 'onUserEvent',
        ];
    }

    public function onUserEvent(UserEvent $userEvent): void
    {

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

    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    protected function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function getConfigKey()
    {
        return $this->getRequest()->attributes->get('_config');
    }

    protected function addError(Session $session, string $message)
    {
        $session->getFlashBag()->add('error', $this->translator->trans($message, [], 'EnhavoUserBundle'));
    }

    protected function generateUrl(string $route, array $options = []): string
    {
        return $this->router->generate($route, $options);
    }

}
