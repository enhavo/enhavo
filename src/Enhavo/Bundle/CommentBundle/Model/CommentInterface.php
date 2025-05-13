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

interface CommentInterface
{
    public const STATE_PENDING = 'pending';
    public const STATE_PUBLISH = 'publish';
    public const STATE_DENY = 'deny';

    /**
     * @return self
     */
    public function setThread(?ThreadInterface $thread);

    /**
     * @return mixed
     */
    public function getThread(): ?ThreadInterface;

    public function publish(): self;

    public function deny(): self;

    public function getSubject(): CommentSubjectInterface;

    public function isStateChanged(): bool;

    public function getState(): string;

    public function setState(string $state): void;
}
