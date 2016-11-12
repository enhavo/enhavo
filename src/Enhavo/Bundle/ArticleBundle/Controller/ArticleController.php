<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ArticleBundle\Entity\Article;

class ArticleController extends ResourceController
{
    public function showResourceAction($article)
    {
        return $this->render('EnhavoArticleBundle:Article:show.html.twig', array(
            'data' => $article
        ));
    }
}
