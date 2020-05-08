<?php

namespace Enhavo\Bundle\ArticleBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\CommentBundle\Comment\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    use TemplateTrait;

    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * ArticleController constructor.
     * @param CommentManager $commentManager
     */
    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    public function showResourceAction($contentDocument = null, Request $request)
    {
        $context = $this->commentManager->handleSubmitForm($request, $contentDocument);
        if($context->isInsert()) {
            $this->redirect($request->getRequestUri());
        }
        return $this->render($this->getTemplate('theme/resource/article/show.html.twig'), array(
            'resource' => $contentDocument,
            'commentForm' => $context->getForm()
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

        return $this->showResourceAction($article, $request);
    }
}
