<?php

namespace Enhavo\Bundle\ShopBundle\Order;

use Enhavo\Bundle\UserBundle\Model\UserInterface;

interface CartMergerInterface
{
    public function merge(UserInterface $user);
}
