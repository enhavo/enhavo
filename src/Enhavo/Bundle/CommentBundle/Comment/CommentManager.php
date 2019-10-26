<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:47
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\CommentBundle\Event\PostCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PostPublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PreCreateCommentEvent;
use Enhavo\Bundle\CommentBundle\Event\PrePublishCommentEvent;
use Enhavo\Bundle\CommentBundle\Exception\CommentSubjectException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Class CommentManager
 * @package Enhavo\Bundle\CommentBundle\Comment
 */
class CommentManager
{
    /**
     * @var FactoryInterface
     */
    private $threadFactory;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string
     */
    private $submitForm;

    /**
     * @var FactoryInterface
     */
    private $commentFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CommentManager constructor.
     * @param FactoryInterface $threadFactory
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param string $submitForm
     * @param FactoryInterface $commentFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FactoryInterface $threadFactory,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        string $submitForm,
        FactoryInterface $commentFactory,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->threadFactory = $threadFactory;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->submitForm = $submitForm;
        $this->commentFactory = $commentFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

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

    /**
     * @param CommentInterface $comment
     */
    public function saveComment(CommentInterface $comment)
    {
        $this->eventDispatcher->dispatch(new PreCreateCommentEvent($comment));
        $this->em->persist($comment);
        $this->em->flush();
        $this->eventDispatcher->dispatch(new PostCreateCommentEvent($comment));
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
