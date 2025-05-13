<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Exception;

class TypeException extends \InvalidArgumentException implements CommentExceptionInterface
{
    public static function createTypeException($value, $type)
    {
        return new CommentSubjectException(sprintf('The value needs to be type of "%s" but "%s" given',
            $type,
            get_class($value)
        ));
    }
}
