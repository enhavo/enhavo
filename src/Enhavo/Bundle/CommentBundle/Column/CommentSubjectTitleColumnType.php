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
use Enhavo\Bundle\CommentBundle\Manager\CommentManager;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentSubjectTitleColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly CommentManager $commentManager,
    )
    {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        if (!$resource instanceof CommentInterface) {
            throw TypeException::createTypeException($resource, CommentInterface::class);
        }

        $data->set('value',$this->commentManager->getSubjectTitle($resource));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'column-text',
            'model' => 'TextColumn',
        ]);
    }

    public static function getName(): ?string
    {
        return 'comment_subject_title';
    }
}
