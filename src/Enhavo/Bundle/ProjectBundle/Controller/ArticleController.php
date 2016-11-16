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
    public function showResourceAction($article)
    {
        return $this->render('EnhavoProjectBundle:Theme/Article:show.html.twig', array(
            'article' => $article
        ));
    }

    public function showArticleAction($slug)
    {
        $article = $this->get('enhavo_article.repository.article')->findOneBy(array(
            'slug' => $slug
        ));

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