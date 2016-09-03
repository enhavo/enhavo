<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OrderAddressing extends Constraint
{
    public function validatedBy()
    {
        return 'order_addressing';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}