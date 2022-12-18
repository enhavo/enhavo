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
    private $onFlush = false;

    public function __construct(
        private ProductVariantProxyFactoryInterface $factory,
    ) {}

    public function getSubscribedEvents()
    {
        return [
            Events::postLoad,
            Events::preFlush,
            Events::postFlush,
            Events::prePersist,
        ];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof OrderItem) {
            $this->replaceWithProxy($entity);
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();
        if ($entity instanceof OrderItem) {
            $this->replaceWithOriginal($entity);
            if ($entity->getProduct()) {
                $em->persist($entity->getProduct());
            }
            // prePersist will trigger also on $em->persist. So we don't know if a flush will come next. The "persist" call
            // should not change the entity, so we check if we are inside a flush routine and if not, we replace it with our proxy again
            if (!$this->onFlush) {
                $this->replaceWithProxy($entity);
            }
        }
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $this->onFlush = true;

        $uow = $args->getObjectManager()->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof OrderItem) {
                $this->replaceWithOriginal($entity);
            }
        }

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
        $this->onFlush = false;

        $uow = $args->getObjectManager()->getUnitOfWork();

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
