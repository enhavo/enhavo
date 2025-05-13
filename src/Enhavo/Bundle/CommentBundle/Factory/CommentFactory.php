<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Factory;

use Enhavo\Bundle\CommentBundle\Exception\NotFoundException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ResourceBundle\Repository\FilterRepositoryInterface;

class CommentFactory extends Factory
{
    /**
     * @var FilterRepositoryInterface
     */
    private $threadRepository;

    public function setThreadRepository(FilterRepositoryInterface $repository)
    {
        $this->threadRepository = $repository;
    }

    public function createWithThreadId($threadId)
    {
        $thread = $this->threadRepository->find($threadId);
        if (null === $thread) {
            throw NotFoundException::createNoThreadException($threadId);
        }
        /** @var CommentInterface $comment */
        $comment = $this->createNew();
        $comment->setThread($thread);

        return $comment;
    }
}
