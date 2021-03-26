<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Entity\Product;
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ProductSubscriber implements EventSubscriberInterface
{
    /** @var ProductManager */
    private $productManager;

    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'sylius.product.pre_create' => 'onPreSave',
            'sylius.product.pre_update' => 'onPreSave',
        ];
    }

    public function onPreSave(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof Product && $subject->getCode() === null) {
            $subject->setCode($this->productManager->generateCode());
        }
    }
}
