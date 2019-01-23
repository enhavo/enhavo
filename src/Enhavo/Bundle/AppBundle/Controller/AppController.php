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
                        'label' => 'test',
                        'url' => '/test',
                        'icon' => 'book'
                    ]
                ],
                'views' => [
                    [
                        'id' => 1,
                        'name' => 'test',
                        'type' => 'iframe',
                        'url' => '/admin/view'
                    ]
                ],
                'quick_menu' => [
                    'user' => [
                        'name' => 'Johnny Doe'
                    ],
                    'items' => array(
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
                    )
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