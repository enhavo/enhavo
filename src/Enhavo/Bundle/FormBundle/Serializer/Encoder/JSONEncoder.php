<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
