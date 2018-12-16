<?php

/**
 * ArrayEncoder.php
 *
 * @since 12/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;

class ArrayEncoder implements EncoderInterface
{
    public function encode($data)
    {
        return $data;
    }
}