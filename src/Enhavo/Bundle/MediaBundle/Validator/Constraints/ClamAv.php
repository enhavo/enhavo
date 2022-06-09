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
    const RESULT_OK = 0;
    const RESULT_VIRUS_FOUND = 1;
    const RESULT_SOME_ERROR = 2;

    const RESULT_CODES = [
        self::RESULT_OK => 'No virus found',
        self::RESULT_VIRUS_FOUND => 'Virus(es) found',
        self::RESULT_SOME_ERROR => 'Some error(s) occured',
    ];

    public string $message = "The file did not pass the virus scanner: {{reason}}";
    public string $clamAvPath = "clamscan";

    public function getResultText(int $exitCode): ?string
    {
        return self::RESULT_CODES[$exitCode] ?? null;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
