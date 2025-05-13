<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type\Exception;

class TypeCreateException extends \InvalidArgumentException
{
    public static function missingOption($class, $options)
    {
        return new self(sprintf('Option "type" is missing to create "%s". Given options are [%s]', join(',', $options), $class));
    }
}
