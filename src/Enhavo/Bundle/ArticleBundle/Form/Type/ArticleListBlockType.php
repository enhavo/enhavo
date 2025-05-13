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

use Enhavo\Bundle\ArticleBundle\Entity\ArticleListBlock;
use Enhavo\Bundle\FormBundle\Form\Type\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleListBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pagination', BooleanType::class, [
            'label' => 'article.label.pagination',
            'translation_domain' => 'EnhavoArticleBundle',
        ]);

        $builder->add('limit', IntegerType::class, [
            'label' => 'article.label.limit',
            'translation_domain' => 'EnhavoArticleBundle',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ArticleListBlock::class,
        ]);
    }
}
