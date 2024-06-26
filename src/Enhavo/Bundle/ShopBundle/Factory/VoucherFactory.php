<?php

namespace Enhavo\Bundle\ShopBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ShopBundle\Entity\Voucher;
use Enhavo\Bundle\ShopBundle\Manager\VoucherManager;

class VoucherFactory extends Factory
{
    public function __construct(
        string $className,
        private VoucherManager $voucherManager
    ) {
        parent::__construct($className);
    }

    public function createNew()
    {
        /** @var Voucher $new */
        $new = parent::createNew();
        $new->setCode($this->voucherManager->generateCode());
        return $new;
    }
}
