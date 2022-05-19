<?php
/**
 * PaymentController.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\PaymentBundle\Model\PaymentInterface;
use Enhavo\Bundle\ShopBundle\Entity\PaymentMethod;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    public function __construct(
        private RepositoryInterface $orderRepository,
        private ResourceManager $resourceManager,
        private TemplateManager $templateManager,
    )
    {}

    public function purchaseAction(Request $request)
    {
        $order = $this->getOrder($request);
        return $this->render($this->templateManager->getTemplate('theme/shop/payment/purchase.html.twig'), [
            'order' => $order
        ]);
    }

    public function doPurchaseAction(Request $request)
    {
        $order = $this->getOrder($request);

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        if ($payment === null) {
            return $this->redirectToRoute('sylius_payment_theme_done', [
                'tokenValue' => $payment->getToken()
            ]);
        }

        if ($payment->getState() === PaymentInterface::STATE_CART) {
            $this->resourceManager->update($payment, 'create', 'enhavo_payment');
        }

        return $this->redirectToRoute('sylius_payment_theme_authorize', [
            'tokenValue' => $payment->getToken()
        ]);
    }

    private function getOrder(Request $request): OrderInterface
    {
        $token = $request->get('token');
        if(empty($token)) {
            throw $this->createNotFoundException();
        }

        /** @var OrderInterface $order */
        $order = $this->orderRepository->findOneBy([
            'token' => $token
        ]);

        if(empty($order)) {
            throw $this->createNotFoundException();
        }

        return $order;
    }
}
