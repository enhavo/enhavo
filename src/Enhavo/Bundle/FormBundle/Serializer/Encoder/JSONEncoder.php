<?php


namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;

/**
 * @author gseidel
 */
class JsonEncoder implements EncoderInterface
{
    public function encode($data)
    {
        return json_decode($data);
    }
}
