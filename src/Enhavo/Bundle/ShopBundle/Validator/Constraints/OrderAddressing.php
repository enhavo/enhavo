<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class OrderAddressing extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
