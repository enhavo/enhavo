<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductAttributeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ProductManager $productManager
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
        if ($subject instanceof ProductAttributeInterface) {
            $this->productManager->updateAttribute($subject);
        }
    }
}
