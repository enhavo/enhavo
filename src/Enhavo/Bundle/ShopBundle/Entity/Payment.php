<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-20
 * Time: 11:41
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Payment\Model\Payment as SyliusPayment;

class Payment extends SyliusPayment
{
    private ?OrderInterface $order;

    public function getOrder(): ?OrderInterface
    {
        return $this->order;
    }

    public function setOrder(?OrderInterface $order): void
    {
        $this->order = $order;
    }
}
