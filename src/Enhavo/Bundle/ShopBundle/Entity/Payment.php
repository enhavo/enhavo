<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-20
 * Time: 11:41
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\PaymentBundle\Entity\Payment as EnhavoPayment;

class Payment extends EnhavoPayment
{
    private ?OrderInterface $order = null;

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }
}
