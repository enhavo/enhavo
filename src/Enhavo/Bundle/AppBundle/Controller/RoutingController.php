<?php
/**
 * RoutingController.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoutingController extends Controller
{
    public function showAction($contentDocument)
    {
        return $this->render('EnhavoAppBundle:Routing:show.html.twig', array(
            'class' => get_class($contentDocument)
        ));
    }
}