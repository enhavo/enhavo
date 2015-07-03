<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 02.09.14
 * Time: 18:26
 */

namespace Enhavo\Bundle\ArticleBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleType;

class ArticleResolver
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getArticle(Request $request)
    {
        if($request->get('id') == 'preview' && $request->getMethod() === 'POST')
        {
            $article = $this->getPreviewArticle($request);
        } else {
            $article = $this->getLiveArticle($request);
        }

        return $article;
    }

    public function getPreviewArticle(Request $request)
    {
        /** @var $formFactory FormFactory */
        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create('enhavo_article_article');
        $form->submit($request);
        return $form->getData();
    }

    public function getLiveArticle(Request $request)
    {
        /** @var $doctrine EntityManager */
        $doctrine = $this->container->get('doctrine');
        $id = $request->get('id');

        $repository = $this->container->get('enhavo_article.repository.article');
        $article = $repository->find($id);
        return $article;
    }
} 