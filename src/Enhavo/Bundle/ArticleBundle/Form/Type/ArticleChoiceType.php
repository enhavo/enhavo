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

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleChoiceType extends AbstractType
{
    public function getParent()
    {
        return AutoCompleteEntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Article::class,
            'choice_label' => 'title',
            'label' => 'article.label.article',
            'translation_domain' => 'EnhavoArticleBundle',
            'multiple' => false,
            'route' => 'enhavo_article_article_auto_complete',
        ]);
    }
}
