<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\AppBundle\Event\ResourceEvent;
use Enhavo\Bundle\AppBundle\Event\ResourceEvents;
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductVariantSubscriber implements EventSubscriberInterface
{
    private ProductManager $productManager;

    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResourceEvents::PRE_CREATE => 'onPreSave',
            ResourceEvents::PRE_UPDATE => 'onPreSave',
        ];
    }

    public function onPreSave(ResourceEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof ProductVariant) {
            $this->productManager->updateProductVariant($subject);
        }
    }
}
