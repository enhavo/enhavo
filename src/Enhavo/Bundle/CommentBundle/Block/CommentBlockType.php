<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Block;

use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\CommentBundle\Entity\CommentBlock;
use Enhavo\Bundle\CommentBundle\Factory\CommentBlockFactory;
use Enhavo\Bundle\CommentBundle\Form\Type\CommentBlockType as CommentBlockFormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentBlockType extends AbstractBlockType
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'model' => CommentBlock::class,
            'form' => CommentBlockFormType::class,
            'factory' => CommentBlockFactory::class,
            'template' => 'theme/block/comment.html.twig',
            'label' => 'comment.label.comment',
            'translation_domain' => 'EnhavoCommentBundle',
            'groups' => ['template'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'comment';
    }
}
