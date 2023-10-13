<?php
/**
 * UserController.php
 *
 * @since 13/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Controller\AppController;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\ShopBundle\Address\AddressProviderInterface;
use Enhavo\Bundle\ShopBundle\Address\UserAddressProviderInterface;
use Enhavo\Bundle\ShopBundle\Order\OrderItemTransfer;
use Enhavo\Bundle\ShopBundle\Form\Type\UserAddressType;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Repository\OrderRepository;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    use TemplateResolverTrait;

    public function __construct(
        private AddressProviderInterface $addressProvider,
        private EntityManagerInterface $em,
        private OrderRepository $orderRepository,
        private OrderItemTransfer $orderItemTransfer,
        private CartContextInterface $cartContext,
    )
    {
    }

    public function addressAction(Request $request): Response
    {
        $address = $this->addressProvider->getAddress();

        $form = $this->createForm(UserAddressType::class, $address);

        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->em->flush();
            }
        }

        return $this->render($this->resolveTemplate('theme/shop/user/address.html.twig'), [
            'form' => $form->createView()
        ]);
    }

    public function listOrderAction(Request $request): Response
    {
        $orders = $this->orderRepository->findByUser($this->getUser());

        return $this->render($this->resolveTemplate('theme/shop/user/order-list.html.twig'), [
            'orders' => $orders
        ]);
    }

    public function showOrderAction(Request $request): Response
    {
        $order = $this->getOrder($request);

        return $this->render($this->resolveTemplate('theme/shop/user/order-show.html.twig'), [
            'order' => $order
        ]);
    }

    public function transferOrderAction(Request $request): Response
    {
        $order = $this->getOrder($request);
        $this->orderItemTransfer->transfer($order, $this->cartContext->getCart());

        return $this->redirectToRoute('enhavo_shop_theme_cart_summary');
    }

    private function getOrder(Request $request): OrderInterface
    {
        $number = $request->get('number');
        $order = $this->orderRepository->findOneBy([
            'number' =>  $number,
            'user' => $this->getUser()
        ]);

        if ($order === null) {
            throw $this->createNotFoundException();
        }

        return $order;
    }
}
