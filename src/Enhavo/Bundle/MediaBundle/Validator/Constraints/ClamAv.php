<?php

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ClamAv extends Constraint
{
    public string $message = "The file did not pass the virus scanner: {{reason}}";
    public ?string $clamscanPath = null;

    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
