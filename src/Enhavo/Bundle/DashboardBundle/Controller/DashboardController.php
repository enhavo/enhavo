<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-04-12
 * Time: 00:04
 */

namespace Enhavo\Bundle\DashboardBundle\Controller;

use Enhavo\Component\Type\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends AbstractController
{
    /** @var Factory */
    private $viewFactory;

    /**
     * DashboardController constructor.
     * @param Factory $viewFactory
     */
    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    public function indexAction(Request $request)
    {
        $view = $this->viewFactory->create([
            'type' => 'dashboard'
        ]);

        return $view->getResponse($request);
    }
}
