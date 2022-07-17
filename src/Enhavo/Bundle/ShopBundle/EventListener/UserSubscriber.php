<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Order\CartMergerInterface;
use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CartMergerInterface $cartMerger,
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event)
    {
        $this->cartMerger->merge($event->getUser());
    }
}
