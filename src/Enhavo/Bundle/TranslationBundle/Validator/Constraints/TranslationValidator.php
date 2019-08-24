<?php
namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TranslationValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        //$property = $this->context->getObject()->getName();
        //$data = $this->context->getObject()->getParent()->getData();
        $this->context->buildViolation('(fr) message')->addViolation();
        return;
    }
}
