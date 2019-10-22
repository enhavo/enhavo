<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-21
 * Time: 19:28
 */

namespace Enhavo\Bundle\CommentBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\CommentBundle\Form\CommentSubmitType;
use Enhavo\Bundle\CommentBundle\Repository\CommentRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentsWidgetType extends AbstractWidgetType
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var RequestStack
     */
    private $requestStack;

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

    public function __construct(
        CommentRepository $repository,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        $submitForm,
        FactoryInterface $commentFactory
    ) {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
        $this->formFactory = $formFactory;
        $this->submitForm = $submitForm;
        $this->commentFactory = $commentFactory;
    }

    public function createViewData(array $options, $resource = null)
    {
        $comments = $this->repository->findByThread([
            'thread' => $options['thread']
        ]);

        $formType = $options['form'] !== null ? $options['form'] : $this->submitForm;
        $form = $this->formFactory->create($formType, $this->commentFactory->createNew());

//        if($articles instanceof Pagerfanta) {
//            $page = $options['page'];
//            if($page === null) {
//                $page = $this->requestStack->getCurrentRequest()->get($options['page_parameter']);
//            }
//            $articles->setCurrentPage($page ?  $page : 1);
//        }

        return [
            'resources' => $comments,
            'form' => $form->createView()
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'template' => 'theme/resource/comment/list.html.twig',
            'pagination' => true,
            'limit' => 10,
            'form' => null
        ]);

        $optionsResolver->setRequired('thread');
    }

    public function getType()
    {
        return 'comments';
    }
}
