<?php
/**
 * ArticleController.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    public function showAction($slug)
    {
        $article = $this->get('enhavo_article.repository.article')->findOneBy(array(
            'slug' => $slug
        ));

        return $this->render('EnhavoProjectBundle:Project/Article:show.html.twig', [
            'article' => $article,
        ]);
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

        return $this->render('EnhavoProjectBundle:Project/Article:category.html.twig', [
            'articles' => $matchingArticles,
            'category' => $category,
        ]);
    }

    public function mobileAction($id)
    {
        $article = $this->get('enhavo_article.repository.article')->find($id);

        return $this->render('EnhavoProjectBundle:Project/Article:mobile.html.twig', [
            'article' => $article,
        ]);
    }
}