<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    public function showAction($article)
    {
        return $this->render('EnhavoAppBundle:Resource:show.html.twig', array(
            'data' => $article
        ));
    }
}