<?php

namespace Enhavo\Bundle\ShopBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Enhavo\Bundle\ShopBundle\Entity\OrderItem;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactoryInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

class ProductVariantProxyReplaceSubscriber implements EventSubscriber
{
    public function __construct(
        private ProductVariantProxyFactoryInterface $factory
    ) {}

    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::preFlush,
            Events::postFlush,
            Events::prePersist,
            Events::postPersist,
        ];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof OrderItem) {
            $this->replaceWithProxy($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof OrderItem) {
            $this->replaceWithOriginal($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof OrderItem) {
            $this->replaceWithProxy($entity);
        }
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $uow = $args->getEntityManager()->getUnitOfWork();

        foreach ($uow->getIdentityMap() as $list) {
            foreach ($list as $entity) {
                if ($entity instanceof OrderItem) {
                    $this->replaceWithOriginal($entity);
                }
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $uow = $args->getEntityManager()->getUnitOfWork();

        foreach ($uow->getIdentityMap() as $list) {
            foreach ($list as $entity) {
                if ($entity instanceof OrderItem) {
                    $this->replaceWithProxy($entity);
                }
            }
        }
    }

    private function replaceWithOriginal(OrderItem $orderItem)
    {
        $product = $orderItem->getProduct();
        if ($product instanceof ProductVariantProxyInterface) {
            $orderItem->setProduct($product->getProductVariant());
        }
    }

    private function replaceWithProxy(OrderItem $orderItem)
    {
        $product = $orderItem->getProduct();
        if ($product instanceof ProductVariantInterface) {
            $orderItem->setProduct($this->factory->createNew($product));
        }
    }
}
