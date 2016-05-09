<?php

/**
 * InstallerController.php
 *
 * @since 06/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\InstallerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstallerController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnhavoInstallerBundle::index.html.twig');
    }
}