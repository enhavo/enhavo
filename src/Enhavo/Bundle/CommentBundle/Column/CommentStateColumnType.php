<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\CommentBundle\Column;

use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\CommentBundle\Exception\TypeException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentStateColumnType extends AbstractColumnType
{
    public function createResourceViewData(array $options, $resource)
    {
        if(!$resource instanceof CommentInterface) {
            throw TypeException::createTypeException($resource, CommentInterface::class);
        }

        $stateMap = [
            CommentInterface::STATE_PUBLISH => 'green',
            CommentInterface::STATE_DENY => 'red',
            CommentInterface::STATE_PENDING  => 'orange'
        ];

        $translator = $this->container->get('translator');
        return [
            'value' => $translator->trans(sprintf('comment.label.%s', $resource->getState()), [], 'EnhavoCommentBundle'),
            'color' => $stateMap[$resource->getState()]
        ];
    }

    public function createColumnViewData(array $options)
    {
        $data = parent::createColumnViewData($options);

        $data = array_merge($data, [
            'wrap' => true
        ]);

        return $data;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'component' => 'column-state',
        ]);
    }

    public function getType()
    {
        return 'comment_state';
    }
}
