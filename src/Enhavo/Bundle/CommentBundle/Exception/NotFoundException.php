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

class NotFoundException extends \InvalidArgumentException implements CommentExceptionInterface
{
    public static function createNoThreadException($id)
    {
        return new CommentSubjectException(sprintf('Can\'t find any thread with id "%s"', $id));
    }
}
