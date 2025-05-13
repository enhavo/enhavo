<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Model;

interface CommentSubjectInterface
{
    public function getThread(): ?ThreadInterface;

    /**
     * @return self
     */
    public function setThread(?ThreadInterface $thread);
}
