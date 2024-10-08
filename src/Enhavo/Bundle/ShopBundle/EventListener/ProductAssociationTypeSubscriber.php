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
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductAssociationTypeSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ProductManager $productManager,
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
        if ($subject instanceof ProductAssociationTypeInterface) {
            $this->productManager->updateAssociationType($subject);
        }
    }
}
