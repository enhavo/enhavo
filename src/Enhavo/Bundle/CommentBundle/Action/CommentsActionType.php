<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 12:42
 */

namespace Enhavo\Bundle\CommentBundle\Action;

use Enhavo\Bundle\AppBundle\Action\AbstractActionType;
use Enhavo\Bundle\AppBundle\Action\ActionLanguageExpression;
use Enhavo\Bundle\CommentBundle\Exception\CommentSubjectException;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentsActionType extends AbstractActionType
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(TranslatorInterface $translator, ActionLanguageExpression $actionLanguageExpression, RouterInterface $router)
    {
        parent::__construct($translator, $actionLanguageExpression);
        $this->router = $router;
    }

    public function createViewData(array $options, $resource = null)
    {
        if(!$resource instanceof CommentSubjectInterface) {
            throw CommentSubjectException::createTypeException($resource);
        }

        $url = $this->router->generate($options['route'], [
            'id' => $resource->getThread()->getId()
        ]);

        $data = parent::createViewData($options, $resource);
        $data['url'] = $url;
        $data['target'] = '_view';
        $data['key'] = 'comment-view';
        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'open-action',
            'route' => 'enhavo_comment_comment_index',
            'label' => 'comment.label.comments',
            'translation_domain' => 'EnhavoCommentBundle',
            'icon' => 'comment'
        ]);
    }

    public function getType()
    {
        return 'comments';
    }
}
