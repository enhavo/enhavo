<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    use TemplateTrait;

    public function showResourceAction($contentDocument)
    {
        return $this->render($this->getTemplate('theme/resource/article/show.html.twig'), array(
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
