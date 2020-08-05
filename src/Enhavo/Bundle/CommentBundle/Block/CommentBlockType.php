<?php

namespace Enhavo\Bundle\CommentBundle\Block;

use Enhavo\Bundle\CommentBundle\Entity\CommentBlock;
use Enhavo\Bundle\CommentBundle\Factory\CommentBlockFactory;
use Enhavo\Bundle\CommentBundle\Form\Type\CommentBlockType as CommentBlockFormType;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
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
            'groups' => ['template']
        ]);
    }

    public static function getName(): ?string
    {
        return 'comment';
    }
}
