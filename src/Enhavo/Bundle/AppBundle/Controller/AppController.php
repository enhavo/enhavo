<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Viewer\ViewerFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\ViewHandler;

class AppController extends Controller
{
    /**
     * @var SimpleRequestConfigurationFactoryInterface
     */
    protected $requestConfigurationFactory;

    /**
     * @var ViewerFactory
     */
    protected $viewerFactory;

    /**
     * @var ViewHandler
     */
    protected $viewHandler;

    public function __construct(
        SimpleRequestConfigurationFactoryInterface $requestConfigurationFactory,
        ViewerFactory $viewerFactory,
        ViewHandler $viewHandler
    ) {
        $this->viewerFactory = $viewerFactory;
        $this->viewHandler = $viewHandler;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
    }

    public function indexAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $viewerFactory = $this->get('viewer.factory');
        $viewer = $viewerFactory->createType($configuration, 'app');
        return $this->viewHandler->handle($viewer->createView());
    }

    public function showAction($contentDocument, Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $viewerFactory = $this->get('viewer.factory');
        $viewer = $viewerFactory->create(
            $configuration,
            null,
            $contentDocument,
            null,
            'base'
        );
        return $this->viewHandler->handle($viewer->createView());
    }
}