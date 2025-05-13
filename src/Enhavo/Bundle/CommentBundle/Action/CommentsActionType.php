<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\CommentBundle\Exception\CommentSubjectException;
use Enhavo\Bundle\CommentBundle\Model\CommentSubjectInterface;
use Enhavo\Bundle\ResourceBundle\Action\AbstractActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class CommentsActionType extends AbstractActionType
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function createViewData(array $options, Data $data, ?object $resource = null): void
    {
        if (!$resource instanceof CommentSubjectInterface) {
            throw CommentSubjectException::createTypeException($resource);
        }

        $url = $this->router->generate($options['route'], [
            'id' => $resource->getThread()->getId(),
        ]);

        $data['url'] = $url;
        $data['target'] = '_frame';
        $data['frameKey'] = 'comment-view';
    }

    public function isEnabled(array $options, ?object $resource = null): bool
    {
        if (!$resource instanceof CommentSubjectInterface) {
            throw CommentSubjectException::createTypeException($resource);
        }

        if (null === $resource->getThread()?->getId()) {
            return false;
        }

        return $this->parent->isEnabled($options, $resource);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'action-action',
            'model' => 'OpenAction',
            'route' => 'enhavo_comment_admin_thread_comment_index',
            'label' => 'comment.label.comments',
            'translation_domain' => 'EnhavoCommentBundle',
            'icon' => 'comment',
        ]);
    }

    public static function getName(): ?string
    {
        return 'comments';
    }
}
