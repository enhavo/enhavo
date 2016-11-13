<?php
/**
 * ShopController.php
 *
 * @since 12/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnhavoProjectBundle:Theme:Shop/index.html.twig');
    }
}