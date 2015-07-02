<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 02.09.14
 * Time: 18:26
 */

namespace Enhavo\Bundle\NewsBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Enhavo\Bundle\NewsBundle\Form\Type\NewsType;

class NewsResolver
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getNews(Request $request)
    {
        if($request->get('id') == 'preview' && $request->getMethod() === 'POST')
        {
            $news = $this->getPreviewNews($request);
        } else {
            $news = $this->getLiveNews($request);
        }

        return $news;
    }

    public function getPreviewNews(Request $request)
    {
        /** @var $formFactory FormFactory */
        $formFactory = $this->container->get('form.factory');
        $form = $formFactory->create('enhavo_news_news');
        $form->submit($request);
        return $form->getData();
    }

    public function getLiveNews(Request $request)
    {
        /** @var $doctrine EntityManager */
        $doctrine = $this->container->get('doctrine');
        $id = $request->get('id');

        $repository = $this->container->get('enhavo_news.repository.news');
        $news = $repository->find($id);
        return $news;
    }
} 