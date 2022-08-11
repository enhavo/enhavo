<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 29.12.16
 * Time: 09:36
 */

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\ShopBundle\Repository\OrderRepository;

class OrderNumberGenerator implements OrderNumberGeneratorInterface
{
    public function __construct(
        private OrderRepository $repository)
    {
    }

    public function generate()
    {
        $order = $this->repository->findLastNumber();

        if (count($order)) {
            $number = $order[0]->getNumber();
            if($number !== null) {
                $number++;
                return $number;
            }
        }

        return 1;
    }
}
