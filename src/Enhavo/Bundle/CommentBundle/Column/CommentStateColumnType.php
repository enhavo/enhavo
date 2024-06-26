<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 02.02.16
 * Time: 11:18
 */

namespace Enhavo\Bundle\CommentBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\CommentBundle\Exception\TypeException;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentStateColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    )
    {
    }

    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {
        if (!$resource instanceof CommentInterface) {
            throw TypeException::createTypeException($resource, CommentInterface::class);
        }

        $stateMap = [
            CommentInterface::STATE_PUBLISH => 'green',
            CommentInterface::STATE_DENY => 'red',
            CommentInterface::STATE_PENDING  => 'orange'
        ];


        $data->set('value', $this->translator->trans(sprintf('comment.label.%s', $resource->getState()), [], 'EnhavoCommentBundle'));
        $data->set('color', $stateMap[$resource->getState()]);
    }

    public function createColumnViewData(array $options, Data $data): void
    {
        $data->set('wrap', true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-state',
        ]);
    }

    public static function getName(): ?string
    {
        return 'comment_state';
    }
}
