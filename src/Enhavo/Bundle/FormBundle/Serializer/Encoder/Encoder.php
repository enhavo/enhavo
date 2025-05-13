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

use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * @author gseidel
 */
class Encoder
{
    public function getEncoder($format)
    {
        if ('json' == $format) {
            return new JsonEncoder();
        }

        if ('array' == $format) {
            return new ArrayEncoder();
        }

        throw new \InvalidArgumentException(sprintf('format "%s" not supported', $format));
    }
}
