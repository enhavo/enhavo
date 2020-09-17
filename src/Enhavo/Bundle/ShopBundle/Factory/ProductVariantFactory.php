<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Doctrine\ORM\EntityRepository;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductVariantFactory implements ProductVariantFactoryInterface
{

    /** @var FactoryInterface */
    private $factory;

    /** @var EntityRepository
     */
    private $repository;

    public function __construct(FactoryInterface $factory, EntityRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }

    /**
     * @return ProductVariantInterface
     */
    public function createNew(): ProductVariantInterface
    {
        return $this->factory->createNew();
    }

    public function createForProduct(ProductInterface $product): ProductVariantInterface
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->createNew();
        $variant->setProduct($product);

        return $variant;
    }

    /**
     * @param int $productId
     * @return ProductVariantInterface
     */
    public function createForProductWithId($productId): ProductVariantInterface
    {
        /** @var ProductInterface $product */
        $product = $this->repository->findOneBy([
            'id' => $productId
        ]);

        /** @var ProductVariantInterface $variant */
        $variant = $this->createNew();
        $variant->setProduct($product);

        return $variant;
    }
}