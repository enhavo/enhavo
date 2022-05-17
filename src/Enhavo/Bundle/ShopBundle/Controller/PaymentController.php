<?php
/**
 * PaymentController.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Enhavo\Bundle\AppBundle\Resource\ResourceManager;
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
    )
    {}

    public function purchaseAction(Request $request)
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

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        if ($payment === null) {
            return $this->redirectToRoute('sylius_payment_theme_done', [
                'tokenValue' => $payment->getToken()
            ]);
        }

        if ($payment->getState() === PaymentInterface::STATE_CART) {
            $this->resourceManager->update($payment, [
                'transition' => 'create',
                'graph' => 'enhavo_payment'
            ]);
        }

        return $this->redirectToRoute('sylius_payment_theme_authorize', [
            'tokenValue' => $payment->getToken()
        ]);
    }
}
