<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductVariantFactory extends Factory implements ProductVariantFactoryInterface
{
    public function __construct(
        string $class,
        private RequestStack $requestStack,
        private EntityRepository $repository,
    ) {
        parent::__construct($class);
    }

    /**
     * {@inheritdoc}
     */
    public function createForProduct(ProductInterface $product): ProductVariantInterface
    {
        /** @var ProductVariantInterface $variant */
        $variant = $this->createNew();
        $variant->setCurrentLocale($this->requestStack->getCurrentRequest()->getLocale());
        $variant->setFallbackLocale($this->requestStack->getCurrentRequest()->getLocale());
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
