<?php
/**
 * ArticleController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Enhavo\Bundle\ArticleBundle\Controller\ArticleController as EnhavoArticleController;

class ArticleController extends EnhavoArticleController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoProjectBundle:Theme/Article:show.html.twig', array(
            'article' => $contentDocument
        ));
    }

    public function showArticleAction($slug)
    {
        $article = $this->get('enhavo_translation.slug_translator')->fetch(
            $slug,
            $this->getParameter('enhavo_article.model.article.class')
        );

        if($article === null) {
            throw $this->createNotFoundException();
        }

        return $this->showResourceAction($article);
    }

    public function categoryAction($slug)
    {
        $articles = $this->get('enhavo_article.repository.article')->findPublished();
        $category = $this->get('enhavo_category.repository.category')->findOneBy(array(
            'slug' => $slug
        ));

        $matchingArticles = [];

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

        return $this->render('EnhavoProjectBundle:Theme/Article:category.html.twig', [
            'articles' => $matchingArticles,
            'category' => $category,
        ]);
    }

    public function mobileAction($id)
    {
        $article = $this->get('enhavo_article.repository.article')->find($id);

        return $this->render('EnhavoProjectBundle:Theme/Article:mobile.html.twig', [
            'article' => $article,
        ]);
    }
}