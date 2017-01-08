<?php

namespace Enhavo\Bundle\ShopBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OrderPayment extends Constraint
{
    public function validatedBy()
    {
        return 'order_payment';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}