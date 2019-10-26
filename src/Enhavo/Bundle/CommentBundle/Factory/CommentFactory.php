<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 18:16
 */

namespace Enhavo\Bundle\CommentBundle\Factory;

use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\CommentBundle\Exception\NotFoundException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;

class CommentFactory extends Factory
{
    /**
     * @var EntityRepositoryInterface
     */
    private $threadRepository;

    /**
     * @param EntityRepositoryInterface $repository
     */
    public function setThreadRepository(EntityRepositoryInterface $repository)
    {
        $this->threadRepository = $repository;
    }

    public function createWithThreadId($threadId)
    {
        $thread = $this->threadRepository->find($threadId);
        if($thread === null) {
            throw NotFoundException::createNoThreadException($threadId);
        }
        /** @var CommentInterface $comment */
        $comment = $this->createNew();
        $comment->setThread($thread);
        return $comment;
    }
}
