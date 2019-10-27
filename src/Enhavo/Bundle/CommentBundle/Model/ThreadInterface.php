<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:58
 */

namespace Enhavo\Bundle\CommentBundle\Model;

use Doctrine\Common\Collections\Collection;

interface ThreadInterface
{
    /**
     * @param CommentSubjectInterface|null $subject
     * @return mixed
     */
    public function setSubject(?CommentSubjectInterface $subject);

    /**
     * @return CommentSubjectInterface|null
     */
    public function getSubject(): ?CommentSubjectInterface;

    /**
     * @param CommentInterface $comment
     */
    public function addComment(CommentInterface $comment);

    /**
     * @param CommentInterface $comment
     */
    public function removeComment(CommentInterface $comment);

    /**
     * @return CommentInterface[]|Collection
     */
    public function getComments(): Collection;

    /**
     * @return bool
     */
    public function isEnable(): bool;

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void;
}
