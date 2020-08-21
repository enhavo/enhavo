<?php

namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Translation extends Constraint
{
    public $constraints;
    public $validateDefaultValue = false;

    public function validatedBy()
    {
        return TranslationValidator::class;
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
