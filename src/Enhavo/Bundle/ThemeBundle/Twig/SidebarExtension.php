<?php
namespace Enhavo\Bundle\ThemeBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SidebarExtension extends \Twig_Extension
{
    protected $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_sidebar', array($this, 'renderSidebar'), array('is_safe' => array('html')))
        );
    }

    public function renderSidebar()
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

        return $templating->render("EnhavoThemeBundle:Default:sidebar.html.twig", [
            'categories'=>$setCategories,
            'articles'=>$articles
        ]);
    }

    public function getName()
    {
        return 'sidebar_extension';
    }
}