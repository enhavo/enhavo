<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                        'icon' => 'fa-book',
                        'notification' => [
                            'class' => 'notification blue',
                            'label' => '120'
                        ],
                        'component' => 'menu-item',
                    ],
                    [
                        'label' => 'Test #2',
                        'url' => '/test',
                        'icon' => 'fa-cube',
                        'notification' => [
                            'class' => 'notification red',
                            'label' => '343'
                        ],
                        'component' => 'menu-list',
                        'items' => [
                            [
                                'label' => 'Test #2.1',
                                'url' => '/test',
                                'icon' => 'fa-university',
                                'notification' => [
                                    'class' => 'success',
                                    'label' => '3'
                                ],
                                'component' => 'menu-item'
                            ],
                            [
                                'label' => 'Test #2.2',
                                'url' => '/test',
                                'icon' => 'fa-beer',
                                'component' => 'menu-item'
                            ],
                            [
                                'label' => 'Test #2.3',
                                'url' => '/test',
                                'icon' => 'book',
                                'component' => 'menu-dropdown',
                                'choices' => [
                                    'hello' => 'world',
                                    'test' => 'test'
                                ]
                            ]
                        ]
                    ],
                    [
                        'label' => 'Test #3',
                        'url' => '/test',
                        'icon' => 'fa-cube',
                        'component' => 'menu-dropdown',
                        'choices' => [
                            'hello' => 'world',
                            'test' => 'test'
                        ]
                    ]
                ],
                'view_stack' => [
                    'width' => 0,
                    'views' => [
                        [
                            'name' => 'Dashboard',
                            'component' => 'iframe-view',
                            'url' => '/admin/view',
                            'loaded' => false
                        ]
                    ],
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
                'page' => 1,
                'pagination' => 100,
                'pagination_steps' => [
                    5, 10, 50, 100, 500
                ],
                'filters' => [
                    [
                        'key' => 'title',
                        'label' => 'Title',
                        'component' => 'Search',
                        'width' => 3,
                        'row' => 1
                    ],
                    [
                        'key' => 'id',
                        'label' => 'id',
                        'component' => 'Text',
                        'width' => 3,
                        'row' => 1
                    ]
                ],
                'sortable' => true,
                'columns' => [
                    [
                        'sortable' => true,
                        'key' => 'title',
                        'label' => 'id',
                        'component' => 'row-column',
                        'rows' => [
                            [
                                'component' => 'text-column',
                                'key' => 'title',
                                'style' => ['bold']
                            ],
                            [
                                'component' => 'text-column',
                                'key' => 'update',
                                'style' => 'grey'
                            ]
                        ],
                        'width' => 3
                    ],
                    [
                        'key' => 'id',
                        'label' => 'Id',
                        'component' => 'Text',
                        'width' => 3,
                    ],
                    [
                        'key' => 'date',
                        'label' => 'Date',
                        'component' => 'Text',
                        'width' => 3,
                    ]
                ],
                'batch' => 'publish',
                'batches' => [
                    [
                        'key' => 'delete',
                        'label' => 'Delete',
                        'uri' => '/action'
                    ],
                    [
                        'key' => 'publish',
                        'label' => 'Publish',
                        'uri' => '/action'
                    ]
                ]
            ]
        ]);
    }

    public function tableAction()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'title' => [
                    'title' => 'Lorem Ipsum',
                    'update' => 'Last Update at'
                ],
                'date' => 'Date'
            ],
            [
                'id' => 2,
                'title' => [
                    'title' => 'Lorem Ipsum2',
                    'update' => 'Last Update at2'
                ],
                'date' => 'Date2'
            ]
        ]);
    }
}