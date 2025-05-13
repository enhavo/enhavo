<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HierarchyNotCircularValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $id = $propertyAccessor->getValue($value, 'id');
        if (null !== $id) {
            $parent = $propertyAccessor->getValue($value, $constraint->parentProperty);
            while (null !== $parent) {
                if ($parent->getId() === $value->getId()) {
                    $this->context->buildViolation($constraint->message)->addViolation();

                    return;
                }
                $parent = $propertyAccessor->getValue($parent, $constraint->parentProperty);
            }
        }
    }
}
