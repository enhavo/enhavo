<?php
/**
 * ShopController.php
 *
 * @since 12/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ProjectBundle\Controller;

use Enhavo\Bundle\ShopBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('EnhavoProjectBundle:Theme:Shop/index.html.twig');
    }

    public function showProductAction(Product $contentDocument)
    {
        return $this->render('EnhavoProjectBundle:Theme/Shop:product.html.twig', [
            'product' => $contentDocument
        ]);
    }
}