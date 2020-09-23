<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Locale;

class ProductVariantFactory extends Factory implements ProductVariantFactoryInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * ProductVariantFactory constructor.
     * @param EntityRepository $repository
     */
    public function __construct(TranslatableFactory $transFactory, RequestStack $requestStack, EntityRepository $repository, string $class)
    {
        parent::__construct($class);
        $this->repository = $repository;
        $this->requestStack = $requestStack;
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