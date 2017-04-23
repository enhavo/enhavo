<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends ResourceController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoArticleBundle:Theme/Article:show.html.twig', array(
            'data' => $contentDocument
        ));
    }

    public function showCategoryAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $slug = $request->get('slug');
        $articles = $this->get('enhavo_article.repository.article')->findPublished();
        $category = $this->get('enhavo_category.repository.category')->findOneBy(array(
            'slug' => $slug
        ));

        $matchingArticles = [];

        /** @var Article $article */
        foreach ($articles as $article) {
            $articleCategories = $article->getCategories();
            $articleCategories = $articleCategories->toArray();

            foreach ($articleCategories as $articleCategory) {
                if ($articleCategory == $category) {
                    $matchingArticles[] = $article;
                    break;
                }
            }
        }

        return $this->render($configuration->getTemplate('EnhavoArticleBundle:Theme/Article:category.html.twig'), [
            'articles' => $matchingArticles,
            'category' => $category,
        ]);
    }
}
