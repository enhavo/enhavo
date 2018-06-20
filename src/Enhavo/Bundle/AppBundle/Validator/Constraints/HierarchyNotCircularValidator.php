<?php

namespace Enhavo\Bundle\AppBundle\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HierarchyNotCircularValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $id = $propertyAccessor->getValue($value, 'id');
        if ($id !== null)
        {
            $parent = $propertyAccessor->getValue($value, $constraint->parentProperty);
            while ($parent !== null) {
                if ($parent->getId() === $value->getId()) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                    return;
                }
                $parent = $propertyAccessor->getValue($parent, $constraint->parentProperty);
            }
        }
    }
}
