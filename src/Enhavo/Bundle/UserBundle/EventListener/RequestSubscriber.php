<?php

namespace Enhavo\Bundle\UserBundle\EventListener;

use Enhavo\Bundle\UserBundle\User\UserManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RequestSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    /** @var SessionInterface */
    private $session;

    /** @var UserManager */
    private $userManager;

    /**
     * RequestSubscriber constructor.
     * @param SessionInterface $session
     * @param UserManager $userManager
     */
    public function __construct(SessionInterface $session, UserManager $userManager)
    {
        $this->session = $session;
        $this->userManager = $userManager;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $config = $request->attributes->get('_config');
        $loginRoute = $this->userManager->getConfig($config, 'login', 'route', 'enhavo_user_theme_security_login');

        if ($event->isMasterRequest() && !$request->isXmlHttpRequest()) {
            if ($loginRoute !== $request->attributes->get('_route')) {
                $this->saveTargetPath($this->session, $this->userManager->getDefaultFirewall(), $request->getUri());
            }
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest']
        ];
    }
}
