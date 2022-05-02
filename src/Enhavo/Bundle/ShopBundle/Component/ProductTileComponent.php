<?php

namespace Enhavo\Bundle\ShopBundle\Component;

use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('shop_product_tile', template: 'theme/component/shop/product-tile.html.twig')]
class ProductTileComponent
{
    #[ExposeInTemplate]
    public ProductInterface $product;

    #[ExposeInTemplate]
    public ProductAccessInterface $productVariant;

    public function __construct(
        private ProductManager $productManager
    )
    {}

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired('product');
        $resolver->setAllowedTypes('product', ProductInterface::class);
        $data = $resolver->resolve($data);

        $this->productVariant = $this->productManager->getDefaultVariantProxy($data['product']);

        return $data;
    }
}
