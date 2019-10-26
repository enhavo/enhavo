<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 08:00
 */

namespace Enhavo\Bundle\CommentBundle\Exception;

class NotFoundException extends \InvalidArgumentException implements CommentExceptionInterface
{
    public static function createNoThreadException($id)
    {
        return new CommentSubjectException(sprintf('Can\'t find any thread with id "%s"', $id));
    }
}
