<?php

namespace Enhavo\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DocumentationController extends AbstractController
{
    public function indexAction(Request $request)
    {
        return $this->render('@EnhavoApi/docs.html.twig');
    }
}
