<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ArticleBundle\Entity\Article;

class ArticleController extends ResourceController
{
    public function showResource($article)
    {
        return $this->render('EnhavoArticleBundle:Article:show.html.twig', array(
            'data' => $article
        ));
    }

    public function batchActionPublish($resources)
    {
        $this->isGrantedOr403('edit');
        $em = $this->get('doctrine.orm.entity_manager');
        /** @var Article $article */
        foreach ($resources as $article) {
            if (!$article->getPublic()) {
                $article->setPublic(true);
                $em->persist($article);
            }
        }
        $em->flush();

        return true;
    }
}
