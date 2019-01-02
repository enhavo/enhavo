<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 02.01.19
 * Time: 21:47
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use Symfony\Component\HttpFoundation\Request;

class PreviewViewHandler
{
    public function createResponse(ViewHandler $handler, View $view, Request $request, $format)
    {
        return $view->getResponse();
    }
}