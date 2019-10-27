<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 08:00
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
