<?php

namespace Enhavo\Bundle\ShopBundle\Block;

use Enhavo\Bundle\AppBundle\View\ViewData;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\ShopBundle\Entity\ProductListBlock;
use Enhavo\Bundle\ShopBundle\Factory\ProductListBlockFactory;
use Enhavo\Bundle\ShopBundle\Form\Type\ProductListBlockType as ProductListBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListBlockType extends AbstractBlockType
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductManager $productManager,
        private RequestStack $requestStack,
    )
    {}

    public function createViewData(BlockInterface $block, ViewData $viewData, $resource, array $options)
    {
        $products = $this->productRepository->findAll();
        $viewData['products'] = $products;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => ProductListBlock::class,
            'parent' => ProductListBlock::class,
            'form' => ProductListBlockFormType::class,
            'factory' => ProductListBlockFactory::class,
            'repository' => 'ProductListBlock::class',
            'template' => 'theme/block/product-list.html.twig',
            'label' => 'ProductList',
            'translation_domain' => 'EnhavoShopBundle',
            'groups' => ['default', 'content']
        ]);
    }

    public static function getName(): ?string
    {
        return 'shop_product_list';
    }
}
