<?php
/**
 * ShopController.php
 *
 * @since 27/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ThemeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function productsAction()
    {
        return $this->render('EnhavoThemeBundle:Theme/Shop:products.html.twig');
    }
}