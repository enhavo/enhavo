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
                        'icon' => 'language',
                        'notification' => [
                            'class' => 'notification blue',
                            'label' => '120'
                        ],
                        'component' => 'menu-item',
                    ],
                    [
                        'label' => 'Test #2',
                        'url' => '/test',
                        'icon' => 'bookmark',
                        'notification' => [
                            'class' => 'notification red',
                            'label' => '343'
                        ],
                        'component' => 'menu-list',
                        'children' => [
                            [
                                'label' => 'Test #2.1',
                                'url' => '/test',
                                'icon' => 'camera',
                                'notification' => [
                                    'class' => 'success',
                                    'label' => '3'
                                ],
                                'component' => 'menu-item'
                            ],
                            [
                                'label' => 'Test #2.2',
                                'url' => '/test',
                                'icon' => 'card_giftcard',
                                'component' => 'menu-item'
                            ],
                            [
                                'label' => 'Test #2.3',
                                'url' => '/test',
                                'icon' => 'clear',
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
                        'icon' => 'control_point',
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

    public function viewAction(Request $request)
    {
        $id = $request->get('view_id');

        return $this->render('EnhavoAppBundle:App:view.html.twig', [
            'data' => [
                'view_id' => $id,
                'page' => [
                    'current' => 1,
                    'last' => 96,
                    'pagination' => 100,
                    'pagination_steps' => [
                        5, 10, 50, 100, 500
                    ]
                ],
                'filters' => [
                    [
                        'key' => 'title',
                        'label' => 'Title',
                        'placeholder' => 'Suchen...',
                        'component' => 'view-table-filter-search',
                        'width' => 3,
                        'row' => 1
                    ],
                    [
                        'key' => 'category',
                        'label' => 'Category',
                        'placeholder' => 'Bitte wählen...',
                        'component' => 'view-table-filter-dropdown',
                        'width' => 3,
                        'row' => 1,
                        'choices' => [
                            'hello' => 'Hello',
                            'world' => 'World',
                            'test' => 'Test'
                        ]
                        ],
                    [
                        'key' => 'id',
                        'label' => 'ID',
                        'component' => 'view-table-filter-boolean',
                        'width' => 1,
                        'row' => 1
                    ],
                    [
                        'key' => 'published',
                        'label' => 'Published',
                        'component' => 'view-table-filter-boolean',
                        'width' => 1,
                        'row' => 1
                    ]
                ],
                'sortable' => true,
                'columns' => [
                    [
                        'key' => 'id',
                        'label' => 'Id',
                        'component' => 'view-table-col-text',
                        'width' => 2,
                        'style' => [
                            'color' => 'red',
                            'background-color' => 'purple'
                        ]
                    ],
                    [
                        'sortable' => true,
                        'key' => 'title',
                        'label' => 'Title',
                        'component' => 'view-table-col-sub',
                        'rows' => [
                            [
                                'component' => 'view-table-col-text',
                                'key' => 'title',
                                'style' => ['bold']
                            ],
                            [
                                'component' => 'view-table-col-text',
                                'key' => 'update',
                                'style' => 'grey'
                            ]
                        ],
                        'width' => 7
                    ],
                    [
                        'key' => 'date',
                        'label' => 'Date',
                        'component' => 'view-table-col-date',
                        'width' => 3,
                    ]
                ],
                'batch' => [
                    'current' => 'publish',
                    'placeholder' => 'Bitte wählen...',
                    'actions' => [
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
                ],
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