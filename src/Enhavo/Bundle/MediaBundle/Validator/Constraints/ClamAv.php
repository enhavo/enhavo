<?php

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ClamAv extends Constraint
{
    public string $message = "The file did not pass the virus scanner: {{reason}}";
    public string $clamAvPath = "clamscan";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
