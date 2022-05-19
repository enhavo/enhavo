<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:47
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\CommentBundle\Event\PostCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PostPublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PreCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PrePublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Exception\CommentSubjectException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentManager
 * @package Enhavo\Bundle\CommentBundle\Comment
 */
class CommentManager
{
    public function __construct(
        private FactoryInterface $threadFactory,
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private string $submitForm,
        private FactoryInterface $commentFactory,
        private EventDispatcherInterface $eventDispatcher,
        private ResourceManager $resourceManager,
    ) {}

    /**
     * @param CommentSubjectInterface $subject
     * @param bool $flush
     */
    public function updateSubject(CommentSubjectInterface $subject, $flush = true)
    {
        if($subject->getThread() === null) {
            /** @var ThreadInterface $thread */
            $thread = $this->threadFactory->createNew();
            $subject->setThread($thread);
            if($flush) {
                $this->em->flush();
            }
        }
    }

    public function createSubmitForm(): FormInterface
    {
        $form = $this->formFactory->create($this->submitForm);
        $comment = $this->commentFactory->createNew();
        $form->setData($comment);
        return $form;
    }

    public function saveComment(CommentInterface $comment)
    {
        $this->resourceManager->save($comment);
    }

    /**
     * @param Request $request
     * @param $subject CommentSubjectInterface
     * @return SubmitContext
     */
    public function handleSubmitForm(Request $request, CommentSubjectInterface $subject): SubmitContext
    {
        $this->checkThread($subject);
        $form = $this->createSubmitForm();
        $form->handleRequest($request);
        $insert = false;
        if($form->isSubmitted() && $form->isValid()) {
            /** @var CommentInterface $comment */
            $comment = $form->getData();
            $comment->setThread($subject->getThread());
            $this->saveComment($comment);
            $form = $this->createSubmitForm();
            $insert = true;
        }
        return new SubmitContext($form, $insert);
    }

    private function checkThread(CommentSubjectInterface $subject)
    {
        $thread = $subject->getThread();
        if($thread === null) {
            throw CommentSubjectException::createNoThreadException($subject);
        }
    }

    public function publishComment(CommentInterface $comment)
    {
        $this->eventDispatcher->dispatch(new PrePublishCommentEvent($comment));
        $comment->publish();
        $this->em->flush();
        $this->eventDispatcher->dispatch(new PostPublishCommentEvent($comment));
    }
}
