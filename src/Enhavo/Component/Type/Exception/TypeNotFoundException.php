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

class TypeNotFoundException extends \InvalidArgumentException
{
    public static function notFound($name, $namespace, $possibilities)
    {
        return new self(sprintf(
            '%s type "%s" not found. Did you mean one of them "%s".',
            $namespace,
            $name,
            implode(', ', $possibilities)
        ));
    }
}
