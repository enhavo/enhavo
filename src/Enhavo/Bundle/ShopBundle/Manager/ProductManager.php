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
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\ShopBundle\Entity\Product;
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactory;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;

class ProductManager
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductVariantProxyFactory $proxyFactory,
        private EngineInterface $engine,
    )
    {}

    public function updateProductVariant(ProductVariant $productVariant)
    {
        if ($productVariant->getCode() === null) {
            $title = empty($productVariant->getTitle()) ? $productVariant->getProduct()->getTitle() : $productVariant->getTitle();
            $productVariant->setCode($this->generateVariantCode($title, $productVariant->getOptionValues()));
        }

        if ($productVariant->getDefault()) {
            foreach ($productVariant->getProduct()->getVariants() as $variant) {
                if ($variant !== $productVariant) {
                    $variant->setDefault(false);
                }
            }
        }

        $this->engine->index($productVariant);
    }

    public function updateProduct(ProductInterface $product)
    {
        if (empty($product->getCode())) {
            $product->setCode($this->generateProductCode($product->getTitle()));
        }

        $variants = $product->getVariants();
        foreach ($variants as $variant) {
            if ($variant instanceof ProductVariant) {
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

    public function getVariantProxies(iterable $productVariants): array
    {
        $proxies = [];
        foreach ($productVariants as $productVariant) {
            $proxies[] = $this->getVariantProxy($productVariant);
        }
        return $proxies;
    }

    public function getVariantProxy(ProductVariantInterface $productVariant): ProductVariantProxyInterface
    {
        return $this->proxyFactory->createNew($productVariant);
    }
}
