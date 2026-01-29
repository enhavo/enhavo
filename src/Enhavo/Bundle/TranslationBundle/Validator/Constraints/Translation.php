<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Translation extends Constraint
{
    public $constraints;
    public $validateDefaultValue = false;

    public function validatedBy(): string
    {
        return TranslationValidator::class;
    }

    public function getTargets(): array|string
    {
        return self::PROPERTY_CONSTRAINT;
    }
}
