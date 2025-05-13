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

use Doctrine\Common\Collections\Collection;

interface ThreadInterface
{
    public function setSubject(?CommentSubjectInterface $subject);

    public function getSubject(): ?CommentSubjectInterface;

    public function addComment(CommentInterface $comment);

    public function removeComment(CommentInterface $comment);

    /**
     * @return CommentInterface[]|Collection
     */
    public function getComments(): Collection;

    public function isEnable(): bool;

    public function setEnable(bool $enable): void;
}
