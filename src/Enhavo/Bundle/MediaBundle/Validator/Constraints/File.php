<?php

namespace Enhavo\Bundle\MediaBundle\Validator\Constraints;

class File extends \Symfony\Component\Validator\Constraints\File
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
