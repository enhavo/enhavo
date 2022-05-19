<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Validator\Constraints;

/**
 * @Annotation
 * @Target({"CLASS"})
 *
 * @property int $maxSize
 *
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class File extends \Symfony\Component\Validator\Constraints\File
{

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
