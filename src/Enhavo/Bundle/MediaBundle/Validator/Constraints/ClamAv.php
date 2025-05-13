<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ClamAv extends Constraint
{
    public string $message = 'The file did not pass the virus scanner: {{reason}}';
    public ?string $clamscanPath = null;

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
