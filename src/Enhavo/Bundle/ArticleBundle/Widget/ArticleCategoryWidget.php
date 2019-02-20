<?php
/**
 * ArticleCategoryWidget.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Widget;

use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Widget\WidgetInterface;

class ArticleCategoryWidget extends AbstractType implements WidgetInterface
{
    public function render($options)
    {
        $templating = $this->container->get("templating");
        $articles = $this->container->get('enhavo_article.repository.article')->findAll();

        // create array
        $setCategories = [];

        // look for all articles
        foreach ($articles as $article) {
            // get category of article with a function defined in the article entity
            $currentCategories = $article->getCategories();
            // convert variable into array
            $currentCategories = $currentCategories->toArray();
            // iterate over array
            foreach ($currentCategories as $currentCategory) {
                // if currentCategory is not in array write in setCategories
                if (!in_array($currentCategory, $setCategories )) {
                    $setCategories[] = $currentCategory;
                }
            }
        }

        return $templating->render('EnhavoArticleBundle:Widget:category.html.twig', [
            'categories' => $setCategories,
            'articles' => $articles
        ]);
    }

    public function getType()
    {
        return 'article_category';
    }
}
