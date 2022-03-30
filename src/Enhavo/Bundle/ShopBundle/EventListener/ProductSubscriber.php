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
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Manager\ProductVariantManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ProductSubscriber implements EventSubscriberInterface
{

    private ProductManager $productManager;
    private ProductVariantManager $productVariantManager;

    public function __construct(ProductManager $productManager, ProductVariantManager $productVariantManager)
    {
        $this->productManager = $productManager;
        $this->productVariantManager = $productVariantManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'sylius.product.pre_create' => 'onPreCreate',
            'sylius.product.pre_update' => 'onPreUpdate',
        ];
    }

    public function onPreCreate(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof Product && $subject->getCode() === null) {
            $subject->setCode($this->productManager->generateCode($subject->getTitle()));
        }
    }

    public function onPreUpdate(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof Product) {
            $variants = $subject->getVariants();
            foreach ($variants as $variant) {
                if ($variant instanceof ProductVariant && $variant->getCode() === null) {
                    $title = empty($variant->getTitle()) ? $subject->getTitle() : $variant->getTitle();
                    $variant->setCode($this->productVariantManager->generateCode($title, $variant->getOptionValues()));
                }
            }
        }
    }
}
