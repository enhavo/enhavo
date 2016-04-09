<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus;

class ArticleController extends ResourceController
{
    public function showResource($article)
    {
        return $this->render('EnhavoArticleBundle:Article:show.html.twig', array(
            'data' => $article
        ));
    }
}