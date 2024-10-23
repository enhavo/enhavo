<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 17:56
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AdminConfirmUrlGenerator implements ConfirmUrlGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Router
     */
    private $entityRouter;

    /**
     * AdminConfirmUrlGenerator constructor.
     * @param RouterInterface $router
     * @param Router $entityRouter
     */
    public function __construct(RouterInterface $router, Router $entityRouter)
    {
        $this->router = $router;
        $this->entityRouter = $entityRouter;
    }

    /**
     * @param CommentInterface $comment
     * @return string
     */
    public function generate(CommentInterface $comment)
    {
        $subjectUrl = $this->entityRouter->generate($comment->getSubject(), [], UrlGeneratorInterface::ABSOLUTE_PATH, 'view');
        $commentListUrl = $this->router->generate('enhavo_comment_admin_comment_index', [
            'id' => $comment->getSubject()->getThread()->getId()
        ]);
        $commentDetailUrl = $this->router->generate('enhavo_comment_admin_comment_update', [
            'id' => $comment->getId()
        ]);

        $state = [
            'views' => [
                [
                    'url' => $subjectUrl,
                    'id' => '1',
                    'storage' => [
                        ['key' => 'comment-view', 'value' => 2]
                    ]
                ],
                [
                    'url' => $commentListUrl,
                    'id' => '2',
                    'storage' => [
                        ['key' => 'edit-view', 'value' => 3]
                    ]
                ],
                [
                    'url' => $commentDetailUrl,
                    'id' => '3'
                ]
            ],
            'storage' => []
        ];

        return $this->router->generate('enhavo_app_index', [
            'state' => StateEncoder::encode($state)
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
