<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 30.10.18
 * Time: 18:24
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\ShopBundle\Entity\Product;
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactory;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;

class ProductManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductVariantProxyFactory $proxyFactory,
    )
    {}

    public function updateProductVariant(ProductVariant $product)
    {
        $title = empty($product->getTitle()) ? $product->getProduct()->getTitle() : $product->getTitle();
        $product->setCode($this->generateVariantCode($title, $product->getOptionValues()));
    }

    public function updateProduct(ProductInterface $product)
    {
        if (empty($product->getCode())) {
            $product->setCode($this->generateProductCode($product->getTitle()));
        }

        $variants = $product->getVariants();
        foreach ($variants as $variant) {
            if ($variant instanceof ProductVariant && $variant->getCode() === null) {
                $this->updateProductVariant($variant);
            }
        }
    }

    public function generateProductCode($name)
    {
        do {
             $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
        } while (!$this->isUnique($code, Product::class));
        return $code;
    }

    public function generateVariantCode($name, $options): string
    {
        $optionString = [];
        /** @var ProductOption $option */
        foreach ($options as $option) {
            $optionString[] = $option->getCode();
        }

        do {
            if (empty($optionString)) {
                $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
            } else {
                $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name) .  '-' . implode('-', $optionString);
            }
        } while (!$this->isUnique($code, ProductVariant::class));
        return $code;
    }

    private function isUnique($code, $dataClass): bool
    {
        $resource = $this->em->getRepository($dataClass)->findOneBy([
            'code' => $code
        ]);
        return $resource === null;
    }

    public function getDefaultVariantProxy(ProductInterface $product): ?ProductAccessInterface
    {
        if ($product->getDefaultVariant()) {
            return $this->proxyFactory->createNew($product->getDefaultVariant());
        }
        return null;
    }
}
