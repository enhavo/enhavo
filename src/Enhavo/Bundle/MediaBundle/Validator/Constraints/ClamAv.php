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
        'No virus found' => self::RESULT_OK,
        'Virus(es) found' => self::RESULT_VIRUS_FOUND,
        'Some error(s) occured' => self::RESULT_SOME_ERROR,
    ];

    public string $message = "The file did not pass the virus scanner: {{reason}}";
    public string $clamAvPath = "clamscan";

    public function getResultText(int $exitCode): string
    {
        return array_search($exitCode, self::RESULT_CODES);
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
