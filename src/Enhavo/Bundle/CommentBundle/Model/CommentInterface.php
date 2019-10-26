<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:58
 */

namespace Enhavo\Bundle\CommentBundle\Model;

interface CommentInterface
{
    const STATE_PENDING = 'pending';
    const STATE_PUBLISH = 'publish';
    const STATE_DENY = 'deny';

    /**
     * @param ThreadInterface $thread
     * @return self
     */
    public function setThread(?ThreadInterface $thread);

    /**
     * @return mixed
     */
    public function getThread(): ?ThreadInterface;

    /**
     * @return self
     */
    public function publish(): self;

    /**
     * @return self
     */
    public function deny(): self;

    /**
     * @return CommentSubjectInterface
     */
    public function getSubject(): CommentSubjectInterface;
}
