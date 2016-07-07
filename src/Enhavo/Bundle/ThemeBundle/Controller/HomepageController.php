<?php

namespace Enhavo\Bundle\ThemeBundle\Controller;

use Enhavo\Bundle\PageBundle\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        $articles = $this->get('enhavo_article.repository.article')->findBy([
            'public' => true
        ]);

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

        return $this->render('EnhavoThemeBundle:Theme:Homepage/index.html.twig', [
            'articles' => $articles,
            'categories' => $setCategories
        ]);
    }
}
