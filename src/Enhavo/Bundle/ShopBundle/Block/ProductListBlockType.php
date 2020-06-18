<?php

namespace Enhavo\Bundle\ShopBundle\Block;

use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Enhavo\Bundle\ShopBundle\Entity\ProductListBlock;
use Enhavo\Bundle\ShopBundle\Factory\ProductListBlockFactory;
use Enhavo\Bundle\ShopBundle\Form\Type\ProductListBlockType as ProductListBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListBlockType extends AbstractBlockType
{
    public function createViewData(BlockInterface $block, $resource, array $options)
    {
        $data = parent::createViewData($block, $resource, $options);
        $data['products'] = $this->container->get('sylius.repository.product')->findAll();
        return $data;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

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

    public function getType()
    {
        return 'shop_product_list';
    }
}
