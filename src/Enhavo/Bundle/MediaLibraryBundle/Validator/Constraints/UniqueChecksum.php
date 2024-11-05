<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueChecksum extends Constraint
{
    public function getTargets(): array|string
    {
        return self::CLASS_CONSTRAINT;
    }
}
