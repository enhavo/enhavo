<?php
/**
 * EncoderInterface.php
 *
 * @since 12/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Serializer\Encoder;


interface EncoderInterface
{
    public function encode($data);
}