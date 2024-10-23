<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 01.02.18
 * Time: 19:58
 */

namespace Enhavo\Bundle\RedirectBundle\Controller;

use Enhavo\Bundle\RedirectBundle\Model\RedirectInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends AbstractController
{
    public function redirectAction(RedirectInterface $contentDocument): Response
    {
        return new RedirectResponse($contentDocument->getTo(), $contentDocument->getCode());
    }
}
