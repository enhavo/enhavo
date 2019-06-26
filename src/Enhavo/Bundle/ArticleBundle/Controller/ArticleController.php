<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    public function showResourceAction($contentDocument)
    {
        return $this->render('EnhavoArticleBundle:Theme/Article:show.html.twig', array(
            'resource' => $contentDocument
        ));
    }

    public function showAction(Request $request)
    {
        $article = $this->get('enhavo_article.repository.article')->findOneBy([
            'slug' => $request->get('slug')
        ]);

        if($article === null) {
            $this->createNotFoundException();
        }

        return $this->showResourceAction($article);
    }
}
