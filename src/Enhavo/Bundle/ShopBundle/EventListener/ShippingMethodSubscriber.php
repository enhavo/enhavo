<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ResourceBundle\Event\ResourceEvent;
use Enhavo\Bundle\ResourceBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Manager\ShippingManager;
use Enhavo\Bundle\UserBundle\Event\UserEvents;
use Sylius\Component\Shipping\Model\ShippingMethodInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShippingMethodSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ShippingManager $shippingManager
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            ResourceEvents::PRE_CREATE => 'onPreSave',
            ResourceEvents::PRE_UPDATE => 'onPreSave',
        ];
    }

    public function onPreSave(ResourceEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof ShippingMethodInterface) {
            $this->shippingManager->updateShippingMethod($subject);
        }
    }
}
