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
use Enhavo\Bundle\ShopBundle\Entity\ProductOption;
use Enhavo\Bundle\ShopBundle\Entity\ProductVariant;
use Enhavo\Bundle\ShopBundle\Factory\ProductVariantProxyFactoryInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantInterface;
use Enhavo\Bundle\ShopBundle\Model\ProductVariantProxyInterface;
use Enhavo\Bundle\ShopBundle\Pricing\PriceCalculatorInterface;
use Enhavo\Bundle\ShopBundle\Product\ProductVariantProxyEnhancerInterface;
use Laminas\Stdlib\PriorityQueue;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;

class ProductManager
{
    /** @var PriorityQueue<PriceCalculatorInterface>|PriceCalculatorInterface[] */
    private PriorityQueue $calculators;

    public function __construct(
        private EntityManagerInterface $em,
        private ProductVariantProxyFactoryInterface $proxyFactory,
        private EngineInterface $engine,
        private TranslationLocaleProviderInterface $translationLocaleProvider,
    )
    {}

    public function updateProductVariant(ProductVariant $productVariant)
    {
        if ($productVariant->getCode() === null) {
            $title = empty($productVariant->getTitle()) ? $productVariant->getProduct()->getTitle() : $productVariant->getTitle();
            $productVariant->setCode($this->generateVariantCode($title, $productVariant->getOptionValues()));
        }

        if ($productVariant->getPosition() === null) {
            $productVariant->setPosition(0);
        }

        if (empty($productVariant->getSlug())) {
            $productVariant->setSlug($this->generateSlug($productVariant->getName()));
        }

        if ($productVariant->isDefault()) {
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
            $product->setCode($this->generateCode($product->getTitle(), get_class($product)));
        }

        $variants = $product->getVariants();
        foreach ($variants as $variant) {
            if ($variant instanceof ProductVariant) {
                $this->updateProductVariant($variant);
            }
        }
    }

    public function updateOption(ProductOptionInterface $option)
    {
        if ($option->getCode() === null) {
            $option->setCode($this->generateCode($option->getName(),  get_class($option)));
        }

        if ($option->getPosition() === null) {
            $option->setPosition(0);
        }

        foreach ($option->getValues() as $value) {
            if ($value->getCode() === null) {
                $value->setCurrentLocale($this->translationLocaleProvider->getDefaultLocaleCode());
                $value->setCode($this->generateCode(sprintf('%s-%s', $option->getName(), $value->getValue()), get_class($value)));
            }
        }
    }

    private function generateCode($name, $dataClass): string
    {
        do {
             $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
        } while (!$this->isUnique($code, $dataClass));
        return $code;
    }

    private function generateVariantCode($name, $options): string
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

    protected function generateSlug($value): string
    {
        return Slugifier::slugify($value);
    }

    private function isUnique($code, $dataClass): bool
    {
        $resource = $this->em->getRepository($dataClass)->findOneBy([
            'code' => $code
        ]);
        return $resource === null;
    }

    public function getDefaultVariantProxies(array $products): array
    {
        $proxies = [];
        foreach ($products as $product) {
            $proxies[] = $this->getDefaultVariantProxy($product);
        }
        return $proxies;
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

    public function updateAttribute(ProductAttributeInterface $attribute)
    {
        if ($attribute->getPosition() === null) {
            $attribute->setPosition(0);
        }

        if ($attribute->getCode() === null) {
            $attribute->setCode($this->generateCode($attribute->getName(), get_class($attribute)));
        }
    }

    public function updateAssociationType(ProductAssociationTypeInterface $associationType)
    {
        if ($associationType->getCode() === null) {
            $associationType->setCode($this->generateCode($associationType->getName(), get_class($associationType)));
        }
    }
}
