<?php
/**
 * OrderConfirmProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Mailer\MailerInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Enhavo\Bundle\ShopBundle\Order\OrderAddressProvider;
use Enhavo\Bundle\ShopBundle\Order\OrderNumberGeneratorInterface;
use Sylius\Component\Cart\Provider\CartProviderInterface;
use Sylius\Component\Core\OrderCheckoutStates;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;

class OrderConfirmProcessor implements ProcessorInterface
{
    /**
     * @var CartProviderInterface
     */
    protected $cartProvider;

    /**
     * @var MailerInterface
     */
    protected $confirmMailer;

    /**
     * @var OrderNumberGeneratorInterface
     */
    protected $numberGenerator;

    /**
     * @var OrderAddressProvider
     */
    protected $orderAddressProvider;

    public function __construct(
        CartProviderInterface $cartProvider,
        MailerInterface $confirmMailer,
        OrderNumberGeneratorInterface $numberGenerator,
        OrderAddressProvider $orderAddressProvider
    )
    {
        $this->cartProvider = $cartProvider;
        $this->confirmMailer = $confirmMailer;
        $this->numberGenerator = $numberGenerator;
        $this->orderAddressProvider = $orderAddressProvider;
    }

    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_COMPLETED);
        $order->setShippingState(ShipmentInterface::STATE_PENDING);
        $order->setPaymentState(PaymentInterface::STATE_PENDING);
        $order->setState(OrderInterface::STATE_CONFIRMED);
        $order->setOrderedAt(new \DateTime);
        if($order->getNumber() === null) {
            $order->setNumber($this->numberGenerator->generate());
        }
        if($order->getUser()) {
            $this->orderAddressProvider->save($order);
        }
        $this->confirmMailer->sendMail($order);
        $this->cartProvider->abandonCart();
    }


}