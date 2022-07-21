<?php

namespace Enhavo\Bundle\ShopBundle\Component;

use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;
use Enhavo\Bundle\ShopBundle\Manager\ProductManager;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('shop_product_configurator', template: 'theme/component/shop/product-configurator.html.twig')]
class ProductConfiguratorComponent
{
    #[ExposeInTemplate]
    public $product;

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
        $resolver->setAllowedTypes('product', [ProductInterface::class, ProductVariantInterface::class, ProductVariantProxyInterface::class]);
        $data = $resolver->resolve($data);

        if ($data['product'] instanceof ProductInterface) {
            $this->productVariant = $this->productManager->getDefaultVariantProxy($data['product']);
        } elseif ($data['product'] instanceof ProductVariantInterface) {
            $this->productVariant = $this->productManager->getVariantProxy($data['product']);
        } else {
            $this->productVariant = $data['product'];
        }

        return $data;
    }
}
