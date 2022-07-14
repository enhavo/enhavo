<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Cart\CartMergerInterface;
use Enhavo\Bundle\UserBundle\Event\UserEvent;
use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private CartMergerInterface $cartMerger,
    )
    {}

    public static function getSubscribedEvents()
    {
        return [
            UserEvent::class => 'onUserEvent',
        ];
    }

    public function onUserEvent(UserEvent $event)
    {
        if ($event->getType() === UserEvent::TYPE_LOGIN_SUCCESS) {
            $this->cartMerger->updateLoggedIn($event->getUser());
        }
    }
}
