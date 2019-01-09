<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewFactory;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    /**
     * @var ViewFactory
     */
    private $viewFactory;

    public function __construct(
        ViewFactory $viewFactory,
        ViewHandlerInterface $viewHandler
    ) {
        $this->viewFactory = $viewFactory;
        $this->viewHandler = $viewHandler;
    }

    public function indexAction(Request $request)
    {
        $view = $this->viewFactory->create('app', [
            'request' => $request
        ]);
        return $this->viewHandler->handle($view);
    }

    public function showAction($contentDocument, Request $request)
    {
        $view = $this->viewFactory->create('base', [
            'request' => $request,
            'resource' => $contentDocument
        ]);
        return $this->viewHandler->handle($view);
    }
}