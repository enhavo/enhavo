<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 29.12.16
 * Time: 09:36
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Doctrine\ORM\EntityManager;

class OrderNumberGenerator implements OrderNumberGeneratorInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function generate()
    {
        $order = $this->em->getRepository('EnhavoShopBundle:Order')->findLastNumber();

        if(count($order)) {
            $number = $order[0]->getNumber();
            if($number !== null) {
                $number++;
                return $number;
            }
        }
        return 1;
    }
}