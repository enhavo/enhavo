<?php

namespace Enhavo\Bundle\ThemeBundle\Controller;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $articles = $this->get('enhavo_article.repository.article')->findAll();
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();

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

        return $this->render('EnhavoThemeBundle:Default:index.html.twig', [
            'articles' => $articles,
            'categories' => $setCategories,
            'pages' => $pages,
            'slides' => $slides
        ]);
    }

    public function indexIpadAction()
    {
        $articles = $this->get('enhavo_article.repository.article')->findAll();
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();

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

        return $this->render('EnhavoThemeBundle:Default:index-ipad.html.twig', [
            'articles' => $articles,
            'categories' => $setCategories,
            'slides' => $slides,
            'pages' => $pages

        ]);
    }

    public function downloadsAction()
    {
        $articles = $this->get('enhavo_article.repository.article')->findAll();
        $downloads = $this->get('enhavo_download.repository.download')->findAll();
        $categories = $this->get('enhavo_category.repository.category')->findAll();
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();

        return $this->render('EnhavoThemeBundle:Default:downloads.html.twig', [
            'articles' => $articles,
            'downloads' => $downloads,
            'categories' => $categories,
            'slides' => $slides,
            'pages' => $pages
        ]);
    }

    public function showArticleAction($slug)
    {
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();
        $article = $this->get('enhavo_article.repository.article')->findOneBy(array(
            'slug' => $slug
        ));
        return $this->render('EnhavoThemeBundle:Default:show.html.twig', [
            'article' => $article,
            'pages' => $pages,
            'slides' => $slides
        ]);
    }


    public function showPageAction($slug)
    {
        $articles = $this->get('enhavo_article.repository.article')->findAll();
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $categories = $this->get('enhavo_category.repository.category')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();
        $page = $this->get('enhavo_page.repository.page')->findOneBy(array(
            'slug' => $slug
        ));
        return $this->render('EnhavoThemeBundle:Default:page.html.twig', [
            'articles' => $articles,
            'pages' => $pages,
            'categories' => $categories,
            'page' => $page,
            'slides' => $slides
        ]);
    }

    public function showCategoryAction($slug)
    {
        $articles = $this->get('enhavo_article.repository.article')->findAll();
        $pages = $this->get('enhavo_page.repository.page')->findAll();
        $categories = $this->get('enhavo_category.repository.category')->findAll();
        $slides = $this->get('enhavo_slider.repository.slide')->findAll();
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

        return $this->render('EnhavoThemeBundle:Default:category.html.twig', [
            'articles' => $matchingArticles,
            'pages' => $pages,
            'categories' => $categories,
            'category' => $category,
            'slides' => $slides
        ]);
    }


}
