<?php
/**
 * Encoder.php
 *
 * @since 12/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\SerializerBundle\Encoder;

use Symfony\Component\Serializer\Encoder\JsonEncoder;

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