<?php
/**
 * OrderConfirmProcessor.php
 *
 * @since 04/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\OrderProcessing;

use Enhavo\Bundle\ShopBundle\Mailer\MailerInterface;
use Enhavo\Bundle\ShopBundle\Manager\CouponManager;
use Enhavo\Bundle\ShopBundle\Manager\VoucherManager;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Enhavo\Bundle\ShopBundle\Model\ProcessorInterface;
use Enhavo\Bundle\ShopBundle\Order\UserAddressProvider;
use Enhavo\Bundle\ShopBundle\Order\OrderNumberGeneratorInterface;
use Sylius\Component\Payment\Model\PaymentInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Enhavo\Bundle\ShopBundle\Order\OrderCheckoutStates;

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
     * @var MailerInterface
     */
    protected $notificationMailer;

    /**
     * @var OrderNumberGeneratorInterface
     */
    protected $numberGenerator;

    /**
     * @var UserAddressProvider
     */
    protected $orderAddressProvider;

    /**
     * @var CouponManager
     */
    protected $couponManager;

    public function __construct(
        CartProviderInterface         $cartProvider,
        MailerInterface               $confirmMailer,
        MailerInterface               $notificationMailer,
        OrderNumberGeneratorInterface $numberGenerator,
        UserAddressProvider           $orderAddressProvider,
        CouponManager                 $couponManager
    )
    {
        $this->cartProvider = $cartProvider;
        $this->confirmMailer = $confirmMailer;
        $this->notificationMailer = $notificationMailer;
        $this->numberGenerator = $numberGenerator;
        $this->orderAddressProvider = $orderAddressProvider;
        $this->couponManager = $couponManager;
    }

    public function process(OrderInterface $order)
    {
        $order->setCheckoutState(OrderCheckoutStates::STATE_COMPLETED);
        $order->setShippingState(ShipmentInterface::STATE_PENDING);
        $order->setPaymentState(PaymentInterface::STATE_PENDING);
        $order->setState(OrderInterface::STATE_CONFIRMED);
        $order->setOrderedAt(new \DateTime);
        $this->couponManager->update($order);
        if($order->getNumber() === null) {
            $order->setNumber($this->numberGenerator->generate());
        }
        if($order->getUser()) {
            $this->orderAddressProvider->save($order);
        }
        $this->confirmMailer->sendMail($order);
        $this->notificationMailer->sendMail($order);
        $this->cartProvider->abandonCart();
    }
}
