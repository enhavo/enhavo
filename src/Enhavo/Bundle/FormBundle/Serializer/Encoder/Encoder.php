<?php

namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;

use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @author gseidel
 */
class Encoder
{
    public function getEncoder($format)
    {
        if($format == 'json') {
            return new JsonEncoder;
        }

        if($format == 'array') {
            return new ArrayEncoder;
        }

        throw new \InvalidArgumentException(sprintf('format "%s" not supported', $format));
    }
}
