<?php
namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Translation extends Constraint
{
    public function validatedBy()
    {
        return TranslationValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
