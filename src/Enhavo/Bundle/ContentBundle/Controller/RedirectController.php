<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 19:58
 */

namespace Enhavo\Bundle\ContentBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\ContentBundle\Model\RedirectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends ResourceController
{
    /**
     * @param $contentDocument RedirectInterface
     * @return Response
     */
    public function redirectAction($contentDocument)
    {
        return new RedirectResponse($contentDocument->getTo(), $contentDocument->getCode());
    }
}