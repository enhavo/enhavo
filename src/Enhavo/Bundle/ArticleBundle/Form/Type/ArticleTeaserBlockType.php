<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Enhavo\Bundle\ArticleBundle\Entity\ArticleTeaserBlock;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleTeaserBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('article', ArticleChoiceType::class);

        $builder->add('layout', ChoiceType::class, [
            'label' => 'article.form.label.layout',
            'translation_domain' => 'EnhavoArticleBundle',
            'choice_translation_domain' => 'EnhavoArticleBundle',
            'choices' => [
                '1:1' => ArticleTeaserBlock::LAYOUT_1_1,
                '1:2' => ArticleTeaserBlock::LAYOUT_1_2,
                '2:1' => ArticleTeaserBlock::LAYOUT_2_1,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);

        $builder->add('textLeft', BooleanType::class, [
            'label' => 'article.form.label.text_left',
            'translation_domain' => 'EnhavoArticleBundle',
            'choice_translation_domain' => 'EnhavoArticleBundle',
            'choices' => [
                'article.form.label.text_left_left' => BooleanType::VALUE_FALSE,
                'article.form.label.text_left_right' => BooleanType::VALUE_TRUE,
            ],
            'expanded' => true,
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleTeaserBlock::class,
        ]);
    }
}
