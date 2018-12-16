<?php

/**
 * ArrayEncoder.php
 *
 * @since 12/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;

class JsonEncoder implements EncoderInterface
{
    public function encode($data)
    {
        return json_decode($data);
    }
}