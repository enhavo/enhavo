<?php
/**
 * AdminConroller.php
 *
 * @since 01/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Controller;

use esperanto\AdminBundle\Model\View;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        $view = new View();
        $view->setParameter('javascripts', $this->container->getParameter('esperanto_admin.javascripts'));
        $view->setParameter('stylesheets', $this->container->getParameter('esperanto_admin.stylesheets'));

        return $this->render('esperantoAdminBundle:Admin:index.html.twig', array(
            'view' => $view
        ));
    }
}