<?php
/**
 * AppController.php
 *
 * @since 08/06/15
 * @author gseidel
 */

namespace esperanto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public function indexAction(Request $request)
    {
        $config = $this->get('viewer.config')->parse($request);
        $viewer = $this->get('viewer.factory')->create($config->getType());
        $viewer->setConfig($config);

        $viewer->dispatchEvent('');

        return $this->render($viewer->getTemplate(), $viewer->getParameters());
    }
}