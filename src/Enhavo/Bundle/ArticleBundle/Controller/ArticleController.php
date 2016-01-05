<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;

class ArticleController extends ResourceController
{
    public function showResource($article)
    {
        return $this->render('EnhavoArticleBundle:Article:show.html.twig', array(
            'data' => $article
        ));
    }
}