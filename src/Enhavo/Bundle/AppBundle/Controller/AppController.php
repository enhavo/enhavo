<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    use AppControllerTrait;

    public function indexAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $viewer = $this->viewerFactory->createType($configuration, 'app');
        return $this->viewHandler->handle($viewer->createView());
    }

    public function showAction($contentDocument, Request $request)
    {
        $configuration = $this->requestConfigurationFactory->createSimple($request);
        $viewer = $this->viewerFactory->create(
            $configuration,
            null,
            $contentDocument,
            null,
            'base'
        );
        return $this->viewHandler->handle($viewer->createView());
    }
}