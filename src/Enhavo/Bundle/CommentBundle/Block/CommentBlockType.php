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
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => CommentBlock::class,
            'parent' => CommentBlock::class,
            'form' => CommentBlockFormType::class,
            'factory' => CommentBlockFactory::class,
            'repository' => 'CommentBlock::class',
            'template' => 'theme/block/comment.html.twig',
            'label' => 'comment.label.comment',
            'translationDomain' => 'EnhavoCommentBundle',
            'groups' => ['template']
        ]);
    }

    public function getType()
    {
        return 'comment';
    }
}
