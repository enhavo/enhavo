<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-17
 * Time: 22:20
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    /**
     * @var MenuManager
     */
    private $menuManager;

    public function __construct(MenuManager $menuManager)
    {
        $this->menuManager = $menuManager;
    }

    public function indexAction(Request $request)
    {
        $data = [
            'view' => [
                'id' => null,
            ],
            'menu' => [
                'items' => $this->menuManager->createMenuViewData(),
                'open' => true
            ],
            'view_stack' => [
                'width' => 0,
                'views' => [],

            ],
            'quick_menu' => [
                'user' => [
                    'name' => 'Johnny Doe'
                ],
                'items' => [
                    [
                        'id' => 1,
                        'name' => 'Test #1',
                        'type' => 'iframe',
                        'url' => '/admin/view'
                    ],
                    [
                        'id' => 2,
                        'name' => 'Test #2',
                        'type' => 'iframe',
                        'url' => '/admin/view'
                    ],
                    [
                        'id' => 3,
                        'name' => 'Test #3',
                        'type' => 'iframe',
                        'url' => '/admin/view'
                    ]
                ]
            ]
        ];

        return $this->render('EnhavoAppBundle:Main:index.html.twig', [
            'data' => $data
        ]);
    }
}
