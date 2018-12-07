<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 06.12.18
 * Time: 23:24
 */

namespace Enhavo;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends AbstractController
{
    public function indexAction()
    {
        return new Response('test');
    }
}