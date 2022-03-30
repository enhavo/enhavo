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

class ProductVariantManager
{
    private EntityManagerInterface $em;

    /**
     * ProductManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function generateCode($name, $options): string
    {
        $optionString = [];
        /** @var ProductOption $option */
        foreach ($options as $option) {
            $optionString[] = $option->getCode();
        }

        do {
            $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name) . '-' . implode('-', $optionString);
        } while (!$this->isUnique($code));
        return $code;
    }

    private function isUnique($code): bool
    {
        $variants = $this->em->getRepository(ProductVariant::class)->findBy([
            'code' => $code
        ]);
        return empty($variants);
    }
}
