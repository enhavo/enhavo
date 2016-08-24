<?php
/**
 * PromotionController.php
 *
 * @since 19/08/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Cart\Model\CartInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PromotionController extends Controller
{
    public function redeemCouponAction(Request $request)
    {
        $order = $this->getCurrentCart();
        $form = $this->createForm('enhavo_shop_order_promotion_coupon', $order);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getManager()->flush();
        }

        if($request->isXmlHttpRequest()) {
            return new JsonResponse();
        }

        $redirectUrl = $request->get('redirectUrl');
        if($redirectUrl) {
            return $this->redirect($redirectUrl);
        }

        return $this->render('EnhavoShopBundle:Promotion:redeem-coupon.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function cancelCouponAction(Request $request)
    {
        /** @var OrderInterface $order */
        $order = $this->getCurrentCart();
        $order->setPromotionCoupon(null);
        $this->getManager()->flush();

        if($request->isXmlHttpRequest()) {
            return new JsonResponse();
        }

        $redirectUrl = $request->get('redirectUrl');
        if($redirectUrl) {
            return $this->redirect($redirectUrl);
        }

        return $this->render('EnhavoShopBundle:Promotion:cancel-coupon.html.twig');
    }

    /**
     * @return CartProviderInterface
     */
    protected function getCartProvider()
    {
        return $this->get('sylius.cart_provider');
    }

    /**
     * @return CartInterface
     */
    protected function getCurrentCart()
    {
        return $this->getCartProvider()->getCart();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getManager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }
}