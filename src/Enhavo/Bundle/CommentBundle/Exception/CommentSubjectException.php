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

use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;

class CommentSubjectException extends \InvalidArgumentException implements CommentExceptionInterface
{
    public static function createNoThreadException($subject)
    {
        return new CommentSubjectException(sprintf(
            'The comment subject "%s" has an empty thread, please provide a thread of type "%s" before continue',
            get_class($subject),
            ThreadInterface::class
        ));
    }

    public static function createTypeException($subject)
    {
        return new CommentSubjectException(sprintf('The subject needs to be type of "%s" but "%s" given',
            CommentSubjectInterface::class,
            get_class($subject)
        ));
    }
}
