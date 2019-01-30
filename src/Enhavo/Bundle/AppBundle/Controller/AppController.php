<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    public function indexAction(Request $request)
    {
        return $this->render('EnhavoAppBundle:App:index.html.twig', [
            'data' => [
                'menu' => [
                    [
                        'label' => 'Test #1',
                        'url' => '/test',
                        'icon' => 'book',
                        'component' => 'menu-item'
                    ],
                    [
                        'label' => 'Test #2',
                        'url' => '/test',
                        'icon' => 'book',
                        'component' => 'menu-list',
                        'items' => [
                            [
                                'label' => 'Test #2.1',
                                'url' => '/test',
                                'icon' => 'book',
                                'component' => 'menu-item'
                            ],
                            [
                                'label' => 'Test #2.2',
                                'url' => '/test',
                                'icon' => 'book',
                                'component' => 'menu-dropdown',
                                'choices' => array([
                                    'hello' => 'world',
                                    'test' => 'test'
                                ])
                            ]
                        ]
                    ],
                    [
                        'label' => 'Test #3',
                        'url' => '/test',
                        'icon' => 'book',
                        'component' => 'menu-dropdown'
                    ]
                ],
                'views' => [
                    [
                        'id' => 1,
                        'name' => 'test',
                        'component' => 'iframe-view',
                        'url' => '/admin/view',
                        'width' => '400'
                    ]
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
            ]
        ]);
    }

    public function viewAction()
    {
        return $this->render('EnhavoAppBundle:App:view.html.twig', [
            'data' => [

            ]
        ]);
    }
}