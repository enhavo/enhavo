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

class ProductManager
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

    public function generateCode($name)
    {
        do {
             $code = substr(md5(microtime()),rand(0,26),4) . '-' . Slugifier::slugify($name);
        } while (!$this->isUnique($code));
        return $code;
    }

    private function isUnique($code) {
        $products = $this->em->getRepository(Product::class)->findBy([
            'code' => $code
        ]);
        return empty($products);
    }
}
