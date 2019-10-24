<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:47
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadAwareInterface;
use Enhavo\Bundle\CommentBundle\Model\ThreadInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * CommentManager constructor.
     * @param FactoryInterface $threadFactory
     * @param EntityManagerInterface $em
     * @param FormFactoryInterface $formFactory
     * @param string $submitForm
     * @param FactoryInterface $commentFactory
     */
    public function __construct(FactoryInterface $threadFactory, EntityManagerInterface $em, FormFactoryInterface $formFactory, string $submitForm, FactoryInterface $commentFactory)
    {
        $this->threadFactory = $threadFactory;
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->submitForm = $submitForm;
        $this->commentFactory = $commentFactory;
    }

    /**
     * @param ThreadAwareInterface $threadAware
     * @param bool $flush
     */
    public function updateThreadAware(ThreadAwareInterface $threadAware, $flush = true)
    {
        if($threadAware->getThread() === null) {
            /** @var ThreadInterface $thread */
            $thread = $this->threadFactory->createNew();
            $threadAware->setThread($thread);
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
     * @param $thread
     * @param bool $flush
     */
    public function saveComment(CommentInterface $comment, $thread, $flush = true)
    {
        $thread = $this->checkThread($thread);
        $comment->setThread($thread);
        $this->em->persist($comment);
        if($flush) {
            $this->em->flush();
        }
    }

    /**
     * @param Request $request
     * @param $thread ThreadAwareInterface|ThreadInterface
     * @return SubmitContext
     */
    public function handleSubmitForm(Request $request, $thread): SubmitContext
    {
        $thread = $this->checkThread($thread);
        $form = $this->createSubmitForm();
        $form->handleRequest($request);
        $insert = false;
        if($form->isSubmitted() && $form->isValid()) {
            $this->saveComment($form->getData(), $thread);
            $form = $this->createSubmitForm();
            $insert = true;
        }
        return new SubmitContext($form, $insert);
    }

    private function checkThread($thread): ThreadInterface
    {
        if($thread instanceof ThreadAwareInterface) {
            return $thread->getThread();
        } else if(!$thread instanceof ThreadInterface) {
            throw new \InvalidArgumentException(sprintf(
                'Thread has to by type of "%s" or "%s" but the type is "%s"', ThreadInterface::class, ThreadAwareInterface::class, get_class($thread)
            ));
        }
        return $thread;
    }
}
