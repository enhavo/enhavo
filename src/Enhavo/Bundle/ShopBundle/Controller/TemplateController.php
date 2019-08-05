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

    public function addressingAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/addressing.html.twig'));
    }

    public function paymentAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/payment.html.twig'));
    }

    public function shippingAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/shipping.html.twig'));
    }

    public function orderSummaryAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/order-summary.html.twig'));
    }

    public function loginAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/login.html.twig'));
    }

    public function finishAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/finish.html.twig'));
    }

    public function registerAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/register.html.twig'));
    }

    public function resetPasswordAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/password-reset.html.twig'));
    }

    public function resetPasswordSuccessAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/password-reset-success.html.twig'));
    }

    public function profileAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/user/profile.html.twig'));
    }

    public function accountAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/user/account.html.twig'));
    }

    public function ordersAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/user/orders.html.twig'));
    }

    public function orderDetailAction()
    {
        return $this->render($this->getTemplate('theme/shop/template/user/order-detail.html.twig'));
    }
}
