<?php

namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;

/**
 * @author gseidel
 */
class ArrayEncoder implements EncoderInterface
{
    public function encode($data)
    {
        return $data;
    }
}
