<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\CommentBundle\Exception\TypeException;
use Enhavo\Bundle\CommentBundle\Manager\CommentManager;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentSubjectLabelColumnType extends AbstractColumnType
{
    public function __construct(
        private readonly CommentManager $commentManager,
    ) {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        if (!$resource instanceof CommentInterface) {
            throw TypeException::createTypeException($resource, CommentInterface::class);
        }

        $data->set('value', $this->commentManager->getSubjectLabel($resource));
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
        return 'comment_subject_label';
    }
}
