<?php
/**
 * UserSubscriber.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Manager\ProductVariantManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class ProductVariantSubscriber implements EventSubscriberInterface
{
    private ProductVariantManager $productVariantManager;

    public function __construct(ProductVariantManager $productVariantManager)
    {
        $this->productVariantManager = $productVariantManager;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product_variant.pre_create' => 'onPreSave',
            'sylius.product_variant.pre_update' => 'onPreSave',
        ];
    }

    public function onPreSave(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if ($subject instanceof ProductVariant && $subject->getCode() === null) {
            $title = empty($subject->getTitle()) ? $subject->getProduct()->getTitle() : $subject->getTitle();
            $subject->setCode($this->productVariantManager->generateCode($title, $subject->getOptionValues()));
        }
    }
}
