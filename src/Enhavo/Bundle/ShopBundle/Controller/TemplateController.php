<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-15
 * Time: 00:22
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TemplateController extends AbstractController
{
    use TemplateTrait;

    public function cartListAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/cart-list.html.twig'));
    }
}
