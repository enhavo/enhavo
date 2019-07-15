<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-15
 * Time: 09:11
 */

namespace Enhavo\Bundle\ArticleBundle\Form\Type;

use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\FormBundle\Form\Type\AutoCompleteEntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleChoiceType
{
    public function getParent()
    {
        return AutoCompleteEntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Article::class,
            'choice_label' => 'article.label.article',
            'translation_domain' => 'EnhavoArticleBundle',
            'multiple' => false,
            'route' => 'enhavo_article_article_auto_complete',
        ]);
    }
}
