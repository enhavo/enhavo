<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-01-20
 * Time: 11:41
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Payum\Core\Model\Payment as BasePayment;

class Payment extends BasePayment
{
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
